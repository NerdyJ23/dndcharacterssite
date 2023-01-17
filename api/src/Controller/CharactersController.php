<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

use App\Controller\Security\AuthenticationController;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;

use App\Schema\Character\CharacterSchema;

class CharactersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function listPublicCharacters() {
		$pagination = new Pagination($this->request);
		$limit = $pagination->getLimit();
		$page = $pagination->getPage();

		$query = $this->Characters->find('all')
			->where(['Characters.Visibility = 1'])
			->contain(['Classes'])
			->limit($limit)
			->page($page);
		$data = $query->all();
		$resultSet = [];
		foreach($data as $item) {
			$resultSet[] = CharacterSchema::toSummarizedSchema($item);
		}
		$this->set("result", $resultSet);
		$this->set("count", sizeOf($resultSet));
		$this->set("page", $page);
		$this->set("limit", $limit);
	}

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');

		$token = $this->request->getCookie('token');
		if ($token == null) {
			return $this->listPublicCharacters($limit, $page);
		} else if (!(new AuthenticationController)->validToken($token)) {
			return $this->listPublicCharacters($limit, $page);
		}

		$userDB = new UsersController();
		$user = $userDB->getByToken($token);

		if ($user == null) { //display only the public characters
			$this->response = $this->response(StatusCodes::TOKEN_MISMATCH);
			return $this->listPublicCharacters($limit, $page);
		}

		$query = $this->Characters->find('all')
			->where(['Characters.User_Access =' => $user->ID])
			->contain(['Classes'])
			->limit($limit)
			->page($page);

			if ($query == null) {
			$this->set('result', []);
			return;
		}

		$data = $query->all()->toArray();
		$resultSet = [];
		foreach($data as $item) {
			$resultSet[] = CharacterSchema::toSummarizedSchema($item);
		}

		$this->set("result", $resultSet);
		$this->set("count", sizeOf($resultSet));
		$this->set("page", $page);
		$this->set("limit", $limit);
	}

	public function get() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = $this->decrypt($id);
		if ($charId == false) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = $this->getById($charId, $token);
		if (sizeOf($result) > 0) {
			$this->set("result", CharacterSchema::toExtendedSchema($result[0]));
			return;
		}
		return $this->response(StatusCodes::NOT_FOUND);
	}

	public function getById($charId, $token) {
		$valid = (new AuthenticationController)->validToken($token);
		if (!$valid) {
			$query = $this->Characters->find('all')
			->where([
				'Characters.ID =' => $charId,
				'Visibility = 1'
			])
			->contain(['Classes', 'Stats', 'Health', 'Background']);
			return $query->all()->toArray();
		} else {
			$userDB = new UsersController();
			$user = $userDB->getByToken($token);
			if ($user == null) {
				return $this->response(StatusCodes::TOKEN_MISMATCH);
			}

			$query = $this->Characters->find('all')
				->where([
					'Characters.ID =' => $charId,
					'OR' => [
						['Characters.Visibility = 1'],
						['Characters.User_Access =' => $user->ID]
					]
			])
			->contain(['Classes', 'Stats', 'Health', 'Background']);
			return $query->all()->toArray();
		}
	}
	public function getCharacterImage() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = $this->decrypt($id);
		if ($charId == false) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$char = $this->getById($charId, $token);
		if (sizeOf($char) == 0) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$filepath = $this->getFilePath($id);
		if (is_file($filepath)) {
			return $this->response->withFile($filepath);
		} else {
			return $this->response(StatusCodes::NO_CONTENT);
		}

		$char = $char[0];
		$this->set('id', $id);
		$this->set('url', $char->Portrait);
		return;
	}
	public function uploadCharacterImage() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');
		$valid = (new AuthenticationController)->validToken($token);
		if (!$valid) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$charId = $this->decrypt($id);
		if ($charId == false) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$userDB = new UsersController();
		$user = $userDB->getByToken($token);
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}

		if ($userId != false) {
			$query = $this->Characters->find('all')
				->where([
					'Characters.ID =' => $charId,
					'Characters.User_Access' => $user->ID
				]);
			$result = $query->all()->toArray();

			if(sizeof($result) == 0) {
				return $this->response(StatusCodes::NOT_FOUND);
			}
			$file = $this->request->getData('image');
			try {
				$file->moveTo($this->getFilePath($id));
			} catch(exception $e) {
				return $this->response(StatusCodes::SERVER_ERROR);
			}
			if (is_file($this->getFilePath($id))) {
				return $this->response(StatusCodes::NO_CONTENT);
			}
			return $this->response(StatusCodes::SERVER_ERROR);
		}
		return $this->response(StatusCodes::TOKEN_MISMATCH);
	}

	private function getFilePath($id) {
		return RESOURCES . 'portraits' . DS . $id . '.png';
	}
}