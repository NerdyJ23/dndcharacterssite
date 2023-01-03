<?php
namespace App\Controller;

use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;

class CharactersStatsController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$token = $this->request->getCookie('token');
		$id = $this->request->getParam("character_id");

		$charDB = new CharactersController();
		$char = $charDB->getById($this->decrypt($id), $token);
		if (sizeOf($char) == 0) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$query = $this->CharactersStats->find('all')
		->where(['Char_ID' => $char[0]->ID]);

		if ($query == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = $query->all()->toArray();
		$this->set("result", sizeOf($result) == 0 ? [] : $result);
		$this->set("page", $page);
		$this->set("limit", $limit);
		$this->set("count", sizeOf($result));
		return;
	}

	public function create() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$name = $this->request->getData("stat");
		$value = $this->request->getData("value");

		if ($token == null) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		if ($name == null || trim($name) == "") {
			$this->set("errorMessage", "Stat name is required and cannot be blank");
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}

		if (!is_numeric($value)) {
			$this->set("errorMessage", "Stat value is required and must be an integer");
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		}

		$userDB = new UsersController();
		$user = $userDB->getByToken($token);

		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		}

		$charDB = new CharactersController();
		$char = $this->_getCharacter($this->decrypt($id));

		if ($char == null || $char->User_Access != $user->ID) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$stat = $this->fetchTable('CharactersStats')->newEntity([
			'Char_ID' => $this->decrypt($id),
			'Name' => trim($name),
			'Value' => $value
		]);

		$result = $this->fetchTable('CharactersStats')->save($stat);

		if ($result) {
			$this->set("result", $result);
			$this->response = $this->response(StatusCodes::CREATED);
			return;
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

	public function update() {
		$name = $this->request->getData("stat");
		$value = $this->request->getData("value");
		$charId = $this->request->getParam("character_id");
		$statId = $this->request->getParam("stat_id");
		$token = $this->request->getCookie('token');

		if ($token == null) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		if ($name == null && $value == null) {
			return $this->response(StatusCodes::USER_ERROR);
		} else if ($value != null && !is_numeric($value)) {
			$this->set("errorMessage", 'Stat value must be an integer');
			$this->response = $this->response(StatusCodes::USER_ERROR);
			return;
		} else if ($name != null && trim($name) == "") {
			$this->set("errorMessage", 'Stat name cannot be blank');
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
		} else if ($user->ID != $char->User_Access) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$query = $this->CharactersStats->find('all')
		->where([
			'ID' => $this->decrypt($statId),
			'Char_ID' => $this->decrypt($charId)
		]);

		if ($query == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$stat = $query->all()->toArray();
		if (sizeOf($stat) == 0) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$stat[0]->Name = $name == null ? $stat[0]->Name : $name;
		$stat[0]->Value = $value == null ? $stat[0]->Value : $value;
		$result = $this->CharactersStats->save($stat[0]);

		if ($result !== false) {
			$this->set("result", $result);
			$this->response = $this->response(StatusCodes::SUCCESS);
			return;
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

	public function delete() {
		$charId = $this->request->getParam("character_id");
		$statId = $this->request->getParam("stat_id");
		$token = $this->request->getCookie('token');

		if ($token == null) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$char = $this->_getCharacter($this->decrypt($charId));
		if ($char == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$userDB = new UsersController();
		$user = $userDB->getByToken($token);
		if ($user == null) {
			return $this->response(StatusCodes::TOKEN_MISMATCH);
		} else if ($user->ID != $char->User_Access) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$query = $this->CharactersStats->find('all')
		->where([
			'ID' => $this->decrypt($statId),
			'Char_ID' => $this->decrypt($charId)
		]);

		if ($query == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$stat = $query->all()->toArray();
		if (sizeOf($stat) == 0) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = $this->CharactersStats->delete($stat[0]);
		if ($result != false) {
			return $this->response(StatusCodes::NO_CONTENT);
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}
	private function _getCharacter($id) {
		$charDB = $this->getTableLocator()->get('Characters');
		$query = $charDB->find('all')
		->where(['ID' => $id]);

		if ($query == null) {
			return null;
		}

		$data = $query->all()->toArray();
		return sizeOf($data) == 0 ? null : $data[0];
	}
}