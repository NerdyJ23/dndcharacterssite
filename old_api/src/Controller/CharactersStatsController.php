<?php
namespace App\Controller;

use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;

use App\Client\Characters\CharactersStatsClient;
use App\Client\Characters\CharactersClient;
use App\Schema\AbstractSchema;

class CharactersStatsController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function list() {
		$pagination = new Pagination($this->request);
		$limit = $pagination->getLimit();
		$page = $pagination->getPage();

		$token = $this->request->getCookie('token');
		$id = $this->request->getParam("character_id");

		if (!CharactersClient::canView(token: $token, charId: $id)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = CharactersStatsClient::list(token: $token, charId: $id, pagination: $pagination);
		$this->set("result", AbstractSchema::schema($result->list, "CharacterStat"));
		$this->set("page", $page);
		$this->set("limit", $limit);
		$this->set("total", $result->total);
		return;
	}

	public function create() {
		$req = $this->request;
		$id = $req->getParam("character_id");
		$token = $req->getCookie('token');
		$stat = (object)[
			'name' => $req->getData('name'),
			'value' => $req->getData('value')
		];

		if (!CharactersClient::canView(token: $token, charId: $id)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if (!CharactersClient::canEdit(token: $token, charId: $id)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		$result = CharactersStatsClient::create(token: $token, charId: $id, stat: $stat);

		if ($result != "") {
			$this->set("id", $result);
			$this->response = $this->response(StatusCodes::CREATED);
			return;
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

	public function update() {
		$req = $this->request;
		$charId = $req->getParam("character_id");
		$statId = $req->getParam("stat_id");
		$token = $req->getCookie('token');

		if (!CharactersClient::canView(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if (!CharactersClient::canEdit(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		if (CharactersStatsClient::read(token: $token, charId: $charId, statId: $statId) == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$stat = (object)[
			'id' => $statId,
			'name' => $req->getData('name'),
			'value' => $req->getData('value')
		];

		$result = CharactersStatsClient::update(token: $token, charId: $charId, stat: $stat);
		if ($result) {
			return $this->response(StatusCodes::NO_CONTENT);
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

	public function delete() {
		$charId = $this->request->getParam("character_id");
		$statId = $this->request->getParam("stat_id");
		$token = $this->request->getCookie('token');
		if (!CharactersClient::canView(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if (!CharactersClient::canEdit(token: $token, charId: $charId)) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}

		if (CharactersStatsClient::read(token: $token, charId: $charId, statId: $statId) == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = CharactersStatsClient::delete(token: $token, charId: $charId, statId: $statId);
		if ($result) {
			return $this->response(StatusCodes::NO_CONTENT);
		}
		return $this->response(StatusCodes::SERVER_ERROR);
	}

}