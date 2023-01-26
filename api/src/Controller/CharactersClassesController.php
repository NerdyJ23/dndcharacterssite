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
		$char = CharactersClient::read(id: $id, token: $token);
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

	public function get() {
		$req = $this->request;
		$id = $req->getParam("character_id");
		$token = $req->getCookie("token");

		if (!CharactersClient::canView(token: $token, charId: $id)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$class = CharactersClassesClient::read(
			classId: $req->getParam('class_id'),
			token: $token,
			charId: $id
		);
		if ($class != null) {
			$this->set('result', AbstractSchema::schema($class, 'CharacterClass'));
			$this->response = $this->response(StatusCodes::SUCCESS);
			return;
		}
		return $this->response(StatusCodes::NOT_FOUND);
	}

	public function create() {
		$req = $this->request;

		$id = $req->getParam("character_id");
		$token = $req->getCookie('token');

		if (!CharactersClient::canView(token: $token, charId: $id)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if (!CharactersClient::canEdit(token: $token, charId: $id)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$class = (object)[
			'name' => $req->getData('name'),
			'level' => $req->getData('level')
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

		if (!CharactersClient::canView(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if (!CharactersClient::canEdit(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}


		$result = CharactersClassesClient::delete(token: $token, charId: $charId, classId: $classId);
		if ($result) {
			return $this->response(StatusCodes::NO_CONTENT);
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}
}