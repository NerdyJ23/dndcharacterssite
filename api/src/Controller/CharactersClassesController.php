<?php
namespace App\Controller;
use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;
use App\Client\Characters\CharactersClient;
use App\Client\Characters\CharactersClassesClient;
use App\Schema\AbstractSchema;
use App\Error\Exceptions\InputException;

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

		if (!CharactersClient::canView(token: $token, charId: $id)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$char = CharactersClient::get(id: $id, token: $token);
		$classes = $this->CharactersClasses->find('all')
			->where(['CharactersClasses.Char_ID' => $char->ID])
			->limit($limit)
			->page($page)
			->all()
			->toArray();

		if (sizeOf($classes) == 0) {
			$this->set("result", []);
			return;
		}

		$this->set("result", AbstractSchema::schema($classes, "CharacterClass"));
	}

	public function create() {
		$name = $this->request->getData("name");
		$level = $this->request->getData("level");
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		if (!CharactersClient::canView(token: $token, charId: $id)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if ($name == null || trim($name) == "") {
			throw new InputException('Class name is required and cannot be blank');
		}

		if (!is_numeric($level)) {
			throw new InputException('Class level must be an integer and cannot be blank');
		}

		if (!CharactersClient::canEdit(token: $token, charId: $id)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$class = (object)[
			'name' => trim($name),
			'level' => (int)$level
		];
		$result = CharactersClassesClient::create(charId: $id, class: $class, token: $token);

		if ($result != "") {
			$this->set("result", $result);
			$this->response = $this->response(StatusCodes::CREATED);
			return;
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

	public function update() {
		$req = $this->request;
		$charId = $req->getParam("character_id");
		$token = $req->getCookie('token');

		if (!CharactersClient::canView(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if (!CharactersClient::canEdit(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$class = (object) [
			'id' => $req->getParam('class_id'),
			'name' => $req->getData("name"),
			'level' =>  $req->getData("level"),
		];

		$result = CharactersClassesClient::update(charId: $charId, class: $class, token: $token);

		if ($result) {
			return $this->response(StatusCodes::NO_CONTENT);
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

	private function _classCount($charId): int {
		$query = $this->CharactersClasses->find('all')
		->where(['Char_ID' => $charId]);

		if ($query == null) {
			return 0;
		}

		return sizeOf($query->all()->toArray());
	}
}