<?php
namespace App\Controller;

use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;
use App\Client\Characters\CharactersHealthClient;
use App\Client\Characters\CharactersClient;

use App\Schema\AbstractSchema;
class CharactersHealthController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function read() {
		$req = $this->request;

		if (!$this->accessAllowed($req->getCookie('token'), $req->getParam('character_id'))) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = CharactersHealthClient::read($req->getParam('health_id'), $req->getParam('character_id'));
		if ($result != null) {
			$this->set('result', AbstractSchema::schema($result, "CharacterHealth"));
			$this->response = $this->response(StatusCodes::SUCCESS);
			return;
		}
		return $this->response(StatusCodes::NOT_FOUND);
	}

	public function update() {
		$req = $this->request;
		if (!$this->accessAllowed($req->getCookie('token'), $req->getParam('character_id'))) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
	}

	private function accessAllowed(string $token, string $charId):bool {
		return CharactersClient::get($this->decrypt($charId), $token) != null;
	}
}