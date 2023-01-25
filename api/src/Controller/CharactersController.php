<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;

use App\Client\Characters\CharactersClient;
use App\Client\Users\UserClient;
use App\Client\Security\AuthClient;
use App\Schema\AbstractSchema;
use App\Controller\Component\Enum\SuccessState;

class CharactersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function listPublicCharacters() {
		$pagination = new Pagination($this->request);
		$result = CharactersClient::listPublic($pagination);
		$resultSet = AbstractSchema::schema($result, "Character");

		$this->set("result", $resultSet);
		$this->set("count", sizeOf($resultSet));
		$this->set("page", $pagination->getPage());
		$this->set("limit", $pagination->getLimit());
		$this->response = $this->response(StatusCodes::SUCCESS);
		return;
	}

	public function list() {
		$pagination = new Pagination($this->request);
		$token = $this->request->getCookie('token');

		if ($token == null || !AuthClient::validToken($token)) {
			AbstractSchema::schema(CharactersClient::listPublic($pagination), "Character");
		}
		$result = CharactersClient::list($pagination, $token);
		$resultSet = AbstractSchema::schema($result, "Character");

		$this->set("result", $resultSet);
		$this->set("count", sizeOf($resultSet));
		$this->set("page", $pagination->getPage());
		$this->set("limit", $pagination->getLimit());
	}

	public function create() {
		$req = $this->request;

		if (!AuthClient::validToken($req->getCookie('token'))) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
		$user = UserClient::getByToken($req->getCookie('token'));
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}
		if ($req->getData('first_name') == null) {
			$this->set('errorMessage', 'Character requires a first name');
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}

		if ($req->getData('race') == null) {
			$this->set('errorMessage', 'Character requires a race');
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}
		$char = (object)[
			'first_name' => $req->getData('first_name'),
			'nickname' => $req->getData('nickname'),
			'last_name' => $req->getData('last_name'),
			'race' => $req->getData('race'),
			'exp' => $req->getData('exp'),
			'alignment' => $req->getData('alignment'),
			// 'user_access' => $user->ID,

			'public' => $req->getData('public'),
			'stats' => $req->getData('stats'),
			'background' => $req->getData('background'),
			'class' => $req->getData('class'),
			'health' => $req->getData('health')
		];
		$result = CharactersClient::create($char, $user->ID);

		if ($result != "") {
			$this->response(StatusCodes::CREATED);
			$this->set("id", $result);
			return;
		} else {
			$this->response(StatusCodes::SERVER_ERROR);
			return;
		}
	}

	public function get() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = $this->decrypt($id);
		if ($charId == false) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = CharactersClient::get($charId, $token);
		if ($result != null) {
			$this->set("result", AbstractSchema::schema($result, "Character"));
			return;
		}
		return $this->response(StatusCodes::NOT_FOUND);
	}

	public function update() {
		$req = $this->request;

		if (!AuthClient::validToken($req->getCookie('token'))) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
		$user = UserClient::getByToken($req->getCookie('token'));
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}

		$char = (object)[
			'id' => $req->getParam('character_id'),
			'first_name' => $req->getData('first_name'),
			'nickname' => $req->getData('nickname'),
			'last_name' => $req->getData('last_name'),
			'race' => $req->getData('race'),
			'exp' => $req->getData('exp'),
			'alignment' => $req->getData('alignment'),

			'public' => $req->getData('public'),
			'stats' => $req->getData('stats'),
			'background' => $req->getData('background'),
			'class' => $req->getData('class'),
			'health' => $req->getData('health')
		];
		$result = CharactersClient::update($char, $user->ID);

		if ($result == SuccessState::SUCCESS) {
			return $this->response(StatusCodes::NO_CONTENT);
		} else if ($result == SuccessState::PARTIAL) {
			$this->response = $this->response(StatusCodes::SUCCESS);
			$this->set('statusMessage', 'Some fields failed to save correctly');
			return;
		} else {
			return $this->response(StatusCodes::SERVER_ERROR);
		}
	}

	public function getCharacterImage() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = $this->decrypt($id);
		if ($charId == false) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$char = CharactersClient::get($charId, $token);
		if ($char == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$filepath = CharactersClient::getFilePath($charId);
		if (is_file($filepath)) {
			return $this->response->withFile($filepath);
		} else {
			return $this->response(StatusCodes::NO_CONTENT);
		}
	}

	public function uploadCharacterImage() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');
		$valid = AuthClient::validToken($token);
		if (!$valid) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$charId = $this->decrypt($id);
		if ($charId == false) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$user = UserClient::getByToken($token);
		if ($user == null) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		if(CharactersClient::get($charId, $token) == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$file = $this->request->getData('image');
		if (CharactersClient::uploadImage($file, $charId)) {
			if (is_file(CharactersClient::getFilePath($charId))) {
				return $this->response(StatusCodes::NO_CONTENT);
			}
			return $this->response(StatusCodes::SERVER_ERROR);
		} else {
			return $this->response(StatusCodes::SERVER_ERROR);
		}
		return $this->response(StatusCodes::TOKEN_MISMATCH);
	}
}