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

	public function list() {
		$pagination = new Pagination($this->request);
		$token = $this->request->getCookie('token');

		if ($token == null || !AuthClient::validToken($token)) {
			AbstractSchema::schema(CharactersClient::listPublic($pagination), "Character");
		}
		$result = CharactersClient::list(pagination: $pagination, token: $token);
		$schema = AbstractSchema::schema($result->list, "Character");
		$this->set("result", $schema);
		$this->set("page", $pagination->getPage());
		$this->set("limit", $pagination->getLimit());
		$this->set("total", $result->total);
	}

	public function create() {
		$req = $this->request;
		$token = $req->getCookie('token');

		if (!AuthClient::validToken($token)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
		$user = UserClient::getByToken($token);
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
			'classes' => $req->getData('classes'),
			'health' => $req->getData('health')
		];
		$result = CharactersClient::create($char, $token);

		if ($result != "" && is_string($result)) {
			$this->response = $this->response(StatusCodes::CREATED);
			$this->set("id", $result);
			return;
		} else {
			return $this->response(StatusCodes::SERVER_ERROR);
		}
	}

	public function get() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$result = CharactersClient::read($id, $token);
		if ($result != null) {
			$this->set("result", AbstractSchema::schema($result, "Character"));
			return;
		}
		return $this->response(StatusCodes::NOT_FOUND);
	}

	public function update() {
		$req = $this->request;
		$token = $req->getCookie('token');

		if (!AuthClient::validToken($token)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
		$user = UserClient::getByToken($token);
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}

		$char = CharactersClient::read($req->getParam('character_id'), $token);
		if ($char == null || $char->User_Access != $user->ID) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$char = (object)[
			'id' => $req->getParam('character_id'),
			'first_name' => $req->getData('first_name'),
			'nickname' => $req->getData('nickname'),
			'last_name' => $req->getData('last_name'),
			'race' => $req->getData('race'),
			'exp' => $req->getData('exp'),
			'alignment' => $req->getData('alignment'),

			'public' => (int)$req->getData('public'),
			'stats' => $req->getData('stats'),
			'background' => $req->getData('background'),
			'classes' => $req->getData('classes'),
			'health' => $req->getData('health'),
			'toDelete' => $req->getData('toDelete')
		];
		$result = CharactersClient::update($char, $token);

		if ($result->status == SuccessState::SUCCESS) {
			return $this->response(StatusCodes::NO_CONTENT);
		} else if ($result->status == SuccessState::PARTIAL) {
			$this->response = $this->response(StatusCodes::SUCCESS);
			$this->set('statusMessage', 'Some fields failed to save correctly');
			$this->set('errorMessage', $result->message);
			return;
		} else {
			return $this->response(StatusCodes::SERVER_ERROR);
		}
	}

	public function getCharacterImage() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$char = CharactersClient::read(id: $id, token: $token);
		if ($char == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$filepath = CharactersClient::getFilePath($id);
		if (is_file($filepath)) {
			return $this->response->withFile($filepath);
		} else {
			return $this->response(StatusCodes::NO_CONTENT);
		}
	}

	public function uploadCharacterImage() {
		$charId = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		if (CharactersClient::read($charId, $token) == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		if (!CharactersClient::canEdit(charId: $charId, token: $token)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
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

	public function removeCharacterImage() {
		$req = $this->request;
		$charId = $req->getParam("character_id");
		$token = $req->getCookie("token");

		if (CharactersClient::canEdit(charId: $charId, token: $token)) {
			if (CharactersClient::removeCharacterImage(charId: $charId, token: $token)) {
				return $this->response(StatusCodes::NO_CONTENT);
			}
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}
}