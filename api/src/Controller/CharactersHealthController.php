<?php
namespace App\Controller;

use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;
use App\Client\Characters\CharactersHealthClient;
use App\Client\Characters\CharactersClient;
use App\Client\Security\AuthClient;
use App\Client\Users\UserClient;
use App\Schema\AbstractSchema;
class CharactersHealthController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function read() {
		$req = $this->request;
		$access = CharactersClient::canView(
			charId: $req->getParam('character_id'),
			token: $req->getCookie('token')
		);
		if (!$access) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$result = CharactersHealthClient::read(
			healthId: $req->getParam('health_id'),
			charId: $req->getParam('character_id'),
			token: $req->getCookie('token')
		);
		if ($result != null) {
			$this->set('result', AbstractSchema::schema($result, "CharacterHealth"));
			$this->response = $this->response(StatusCodes::SUCCESS);
			return;
		}
		return $this->response(StatusCodes::NOT_FOUND);
	}

	public function update() {
		$req = $this->request;
		$access = AuthClient::validToken($req->getCookie('token'));
		if (!$access) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
		if (CharactersClient::canEdit(token: $req->getCookie('token'), charId: $req->getParam('character_id'))) {
			$health = (object) [
				'id' => $req->getParam('health_id'),
				'current_health' => $req->getData('current_health'),
				'max_health' => $req->getData('max_health'),
				'temporary_health' => $req->getData('temporary_health'),
				'hit_die' => $req->getData('hit_die'),
				'death_fails' => $req->getData('death_fails'),
				'death_success' => $req->getData('death_success')
			];

			$result = CharactersHealthClient::update(
				health: $health,
				token: $req->getCookie('token'),
				charId: $req->getParam('character_id')
			);

			if ($result) {
				return $this->response(StatusCodes::NO_CONTENT);
			} else {
				return $this->response(StatusCodes::SERVER_ERROR);
			}
		} else {
			return $this->response(StatusCodes::NOT_FOUND);
		}
	}
}