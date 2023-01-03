<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use Cake\Http\Cookie\Cookie;

class CharactersClassesController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function list() {
		$token = $this->request->getCookie('token');
		$id = $this->request->getParam("character_id");
		$charId = (new EncryptionController)->decrypt($id);

		//Get Character, check if visible to all
		$char = $this->_getCharacter($charId);
		if ($char == null) {
			return $this->response->withStatus(404);
		}

		//If private character check auth
		if ($char->Visibility == 0) {
			if ($token == null) {
				return $this->response->withStatus(404);
			}

			//Get user
			$user = $this->_getUser($token);

			if ($user == null) {
				return $this->response->withStatus(403, 'Token Mismatch');
			}

			if ($user->ID != $char->User_Access) {
				return $this->response->withStatus(403);
			}
		}

		$query = $this->CharactersClasses->find('all')
			->where(['CharactersClasses.Char_ID' => $char->ID]);

		$data = $query->all()->toArray();

		if (sizeOf($data) == 0) {
			$this->set("result", []);
			return;
		}

		$this->set("result", $data);
	}

	public function create() {
		$name = $this->request->getData("class");
		$level = $this->request->getData("level");
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');
		if ($token == null) {
			return $this->response->withStatus(403);
		}

		if ($name == null) {
			return $this->response->withStatus(400, 'Class name is required');
		}

		if (!is_numeric($level)) {
			return $this->response->withStatus(400, 'Class level must be an integer');
		}

		$charId = (new EncryptionController)->decrypt($id);
		$char = $this->_getCharacter($charId);
		if ($char == null) {
			return $this->response->withStatus(404);
		}

		//Only owner can edit
		$user = $this->_getUser($token);
		if ($user->ID != $char->User_Access) {
			return $this->response->withStatus(403);
		}

		$class = $this->fetchTable('CharactersClasses')->newEntity([
			'Char_ID' => $charId,
			'Class' => $name,
			'Level' => $level
		]);

		$result = $this->fetchTable('CharactersClasses')->save($class);
		if ($result) {
			$this->set("result", $result);
			$this->response = $this->response->withStatus(201);
			return;
		}
	}

	private function _getCharacter($id) {
		$charDB = $this->getTableLocator()->get('Characters');
		$charQuery = $charDB->find('all')
			->where(['Characters.ID' => $id]);

		if ($charQuery == null) {
			return null;
		}

		$char = $charQuery->all()->toArray();
		return sizeOf($char) == 0 ? null : $char[0];
	}

	private function _getUser($token) {
		$userDB = $this->getTableLocator()->get('Users');
		$userQuery = $userDB->find('all')
			->where(['Users.Token' => $token]);
		if ($userQuery == null) {
			return null;
		}

		$user = $userQuery->all()->toArray();
		return sizeOf($user) == 0 ? null : $user[0];
	}
}