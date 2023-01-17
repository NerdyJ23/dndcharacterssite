<?php
namespace App\Controller;
use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;

class CharactersClassesController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function list() {
		$pagination = new Pagination($this->request);
		$limit = $pagination->getLimit();
		$page = $pagination->getPage();

		$token = $this->request->getCookie('token');
		$id = $this->request->getParam("character_id");
		$charId = $this->decrypt($id);

		//Get Character, check if visible to all
		$char = $this->_getCharacter($charId);
		if ($char == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		//If private character check auth
		if ($char->Visibility == 0) {
			if ($token == null) {
				return $this->response(StatusCodes::NOT_FOUND);
			}

			//Get user
			$userDB = new UsersController();
			$user = $userDB->getByToken($token);

			if ($user == null) {
				return $this->response(StatusCodes::TOKEN_MISMATCH);
			}

			if ($user->ID != $char->User_Access) {
				return $this->response(StatusCodes::ACCESS_DENIED);
			}
		}

		$query = $this->CharactersClasses->find('all')
			->where(['CharactersClasses.Char_ID' => $char->ID])
			->limit($limit)
			->page($page);

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
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		if ($name == null || trim($name) == "") {
			$this->set("errorMessage", 'Class name is required and cannot be blank');
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}

		if (!is_numeric($level)) {
			$this->set("errorMessage", 'Class level must be an integer and cannot be blank');
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}

		$charId = $this->decrypt($id);
		$char = $this->_getCharacter($charId);
		if ($char == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		//Only owner can edit
		$userDB = new UsersController();
		$user = $userDB->getByToken($token);
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}
		if ($user->ID != $char->User_Access) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$class = $this->fetchTable('CharactersClasses')->newEntity([
			'Char_ID' => $charId,
			'Class' => trim($name),
			'Level' => $level
		]);

		$result = $this->fetchTable('CharactersClasses')->save($class);
		if ($result) {
			$this->set("result", $result);
			$this->response = $this->response(StatusCodes::CREATED);
			return;
		}

		return $this->response(StatusCodes::SERVER_ERROR);
	}

	public function update() {
		$name = $this->request->getData("class");
		$level = $this->request->getData("level");
		$charId = $this->request->getParam("character_id");
		$classId = $this->request->getParam("class_id");
		$token = $this->request->getCookie('token');

		if ($token == null) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		if ($name == null && $level == null) {
			return $this->response(StatusCodes::USER_ERROR);
		} else if ($level != null && !is_numeric($level)) {
			$this->set("errorMessage", 'Class level must be an integer');
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		} else if ($name != null && trim($name) == "") {
			$this->set("errorMessage", 'Class name cannot be blank');
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}


		$char = $this->_getCharacter($this->decrypt($charId));
		if ($char == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$userDB = new UsersController();
		$user = $userDB->getByToken($token);
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}

		if ($user->ID != $char->User_Access) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$class = $this->CharactersClasses->find('all')
		->where([
			'ID' => $this->decrypt($classId),
			'Char_ID' => $this->decrypt($charId)
		]);
		if ($class == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$class = $class->all()->toArray();
		if (sizeOf($class) == 0) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if ($name != null) {
			$class[0]->Class = trim($name);
		}

		if ($level != null) {
			$class[0]->Level = $level;
		}
		$result = $this->CharactersClasses->save($class[0]);

		if ($result !== false) {
			$this->set("result", $result);
			$this->response = $this->response(StatusCodes::SUCCESS);
			return;
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

	public function delete() {
		$charId = $this->request->getParam("character_id");
		$classId = $this->request->getParam("class_id");
		$token = $this->request->getCookie('token');

		if ($token == null) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$userDB = new UsersController();
		$user = $userDB->getByToken($token);
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}

		$char = $this->_getCharacter($this->decrypt($charId));
		if ($char == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if ($user->ID != $char->User_Access) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$class = $this->CharactersClasses->find('all')
		->where([
			'ID' => $this->decrypt($classId),
			'Char_ID' => $this->decrypt($charId)
		]);
		if ($class == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$class = $class->all()->toArray();
		if (sizeOf($class) == 0) {
			return $this->response(StatusCodes::NOT_FOUND);
		} else if ($this->_classCount($char->ID) == 1) {
			$this->set("errorMessage", "Character must have at least one (1) class");
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}

		$result = $this->CharactersClasses->delete($class[0]);
		if ($result != false) {
			return $this->response(StatusCodes::NO_CONTENT);
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

	private function _getCharacter($id) {
		$charDB = $this->getTableLocator()->get('Characters');
		$query = $charDB->find('all')
		->where(['Characters.ID =' => $id]);

		if ($query == null) {
			return null;
		}

		$char = $query->all()->toArray();
		return sizeOf($char) == 0 ? null : $char[0];
	}

	private function _classCount($charId): int {
		$query = $this->CharactersClasses->find('all')
		->where(['Char_ID' => $charId]);

		if ($query == null) {
			return 0;
		}

		return sizeOf($query->all()->toArray());
	}
}