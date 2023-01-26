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
		$access = CharactersClient::get($this->decrypt($req->getParam('character_id')), $req->getCookie('token')) != null;
		if (!$access) {
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
		$access = AuthClient::validToken($req->getCookie('token'));
		if (!$access) {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
		$char = CharactersClient::get($this->decrypt($req->getParam('character_id')), $req->getCookie('token'));
		$user = UserClient::getByToken($req->getCookie('token'));
		if ($char != null && $user != null && $char->User_Access == $user->ID) {
			$health = (object) [
				'id' => $req->getParam('health_id'),
				'current_health' => $req->getData('current_health'),
				'max_health' => $req->getData('max_health'),
				'temporary_health' => $req->getData('temporary_health'),
				'hit_die' => $req->getData('hit_die'),
				'death_fails' => $req->getData('death_fails'),
				'death_success' => $req->getData('death_success')
			];
			$result = CharactersHealthClient::update($health, $user->ID);
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