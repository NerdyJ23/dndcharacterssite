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
}