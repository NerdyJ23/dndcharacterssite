<?php
namespace App\Controller;
use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;
use App\Schema\AbstractSchema;

use App\Client\Security\AuthClient;
use App\Client\Characters\CharactersBackgroundClient;

class CharactersBackgroundController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	//Character should only have one background
	public function list() {
		$token = $this->request->getCookie('token');
		// $validToken = $token == null ? false : AuthClient::validToken($token);

		$id = $this->request->getParam("character_id");
		$background = CharactersBackgroundClient::list($this->decrypt($id), $token);
		$this->set("bg", $background);
		return;
		if ($background == null) {
			return $this->response(StatusCodes::NOT_FOUND);
		}
		$this->set("result", AbstractSchema::schema($background, "CharacterBackground"));
		$this->response = $this->response(StatusCodes::SUCCESS);
		return;
	}
}
