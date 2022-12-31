<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use Cake\Http\Cookie\Cookie;
use DateTime;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class CharactersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function listPublicCharacters() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$query = $this->Characters->find('all')
			->where(['Characters.Visibility = 1'])
			->contain(['Classes'])
			->limit($limit)
			->page($page);
		$data = $query->all()->toArray();
		$resultSet = [];
		foreach($data as $item) {
			$resultSet[] = $this->toSummarizedSchema($item);
		}
		$this->set("result", $resultSet);
	}

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$token = $this->request->getCookie('token');
		if ($token == null) {
			return $this->listPublicCharacters($limit, $page, $count);
		} else if (!(new AuthenticationController)->validToken($token)) {
			return $this->listPublicCharacters($limit, $page, $count);
		}

		$userDB = new UsersController();
		$user = $userDB->get($token);
		if ($user == null || $user == -1) { //display only the public characters
			return $this->listPublicCharacters($limit, $page, $count);
		}

		$userId = (new EncryptionController)->decrypt($user);
		$query = $this->Characters->find('all')
			->where(['Characters.User_Access =' => $userId,
			])
			->contain(['Classes'])
			->limit($limit)
			->page($page);

		if ($query == null) {
			return $this->set('result', []);
		}
		$data = $query->all()->toArray();
		$resultSet = [];
		foreach($data as $item) {
			$resultSet[] = $this->toSummarizedSchema($item);
		}
		$this->set("result", $resultSet);
	}

	public function get() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = (new EncryptionController)->decrypt($id);
		if ($charId == false) {
			return $this->response->withStatus(404);
		}

		$result = $this->getById($charId, $token);
		if (sizeOf($result) > 0) {
			$this->set("result", $this->toExtendedSchema($result[0]));
			return;
		}
		return $this->response->withStatus(404);
	}

	private function getById($charId, $token) {
		$valid = (new AuthenticationController)->validToken($token);
		if (!$valid) {
			$query = $this->Characters->find('all')
			->where([
				'Characters.ID =' => $charId,
				'Visibility = 1'
			])
			->contain(['Classes', 'Stats', 'Health']);
			return $query->all()->toArray();
		} else {
			$userDB = new UsersController();
			$userId = $userDB->get($token);
			$query = $this->Characters->find('all')
				->where([
					'Characters.ID =' => $charId,
					'OR' => [
						['Characters.Visibility = 1'],
						['Characters.User_Access =' => (new EncryptionController)->decrypt($userId)]
					]
			])
			->contain(['Classes', 'Stats', 'Health']);
			return $query->all()->toArray();
		}
	}
	public function getCharacterImage() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = (new EncryptionController)->decrypt($id);
		if ($charId == false) {
			return $this->response->withStatus(404);
		}

		$char = $this->getById($charId, $token);
		if (sizeOf($char) == 0) {
			return $this->response->withStatus(404);
		}
		$filepath = $this->getFilePath($id);
		if (is_file($filepath)) {
			return $this->response->withFile($filepath);
		} else {
			return $this->response->withStatus(204);
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
			return $this->response->withStatus(403);
		}

		$charId = (new EncryptionController)->decrypt($id);
		if ($charId == false) {
			return $this->response->withStatus(404);
		}

		$userDB = $this->getTableLocator()->get('Users');
		$userQuery = $userDB->find('all')
			->where(['Users.Token' => $token]);
		$user = $userQuery->all()->toArray();

		if (sizeOf($user) == 0) {
			return $this->response->withStatus(403, 'Token Mismatch');
		}

		$userId = (new EncryptionController)->decrypt($user[0]->encrypted_id);
		if ($userId != false) {
			$query = $this->Characters->find('all')
				->where([
					'Characters.ID =' => $charId,
					'Characters.User_Access' => $userId
				]);
			$result = $query->all()->toArray();

			if(sizeof($result) == 0) {
				return $this->response->withStatus(404);
			}
			$file = $this->request->getData('image');
			try {
				$file->moveTo($this->getFilePath($id));
			} catch(exception $e) {
				return $this->response->withStatus(500);
			}
			if (is_file($this->getFilePath($id))) {
				return $this->response->withStatus(204);
			}
			return $this->response->withStatus(500);
		}
		return $this->response->withStatus(403, 'Token mismatch');
	}

	private function getFilePath($id) {
		return RESOURCES . 'portraits' . DS . $id . '.png';
	}
	private function toSummarizedSchema($character) {
		return [
			'id' => $character->id,
			'full_name' => $character->Full_Name,
			'classes' => $character->classes,
			'race' => $character->Race,
			'background' => $character->Background,
			'alignment' => $character->Alignment,
			'exp' => $character->Exp
		];
	}
	private function toExtendedSchema($character) {
		return [
			'id' => $character->id,
			'first_name' => $character->First_Name,
			'last_name' => $character->Last_Name,
			'full_name' => $character->Full_Name,
			'race' => $character->Race,
			'exp' => $character->Exp,
			'background' => $character->Background,
			'alignment' => $character->Alignment,
			'visibility' => $character->Visibility,
			'classes' => $character->classes,
			'stats' => $character->stats,
			'health' => $character->health
		];
	}
}