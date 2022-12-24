<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use Cake\Http\Cookie\Cookie;
use DateTime;

class CharactersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function listPublicCharacters() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$query = $this->Characters->find('all')
			->where(['Characters.Visibility = 1'])
			->limit($limit)
			->page($page);
		$data = $query->all()->toArray();
		$this->set("result", $data);
	}

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$token = $this->request->getCookie('token');
		if ($token == null) {
			return $this->listPublicCharacters($limit, $page, $count);
		} else if (!(new AuthenticationController)->validToken($token)) {
			return $this->listPublicCharacters($limit, $page, $count);
		}

		$userDB = new UsersController();
		$user = $userDB->get($token);
		if ($user == null || $user == -1) { //display only the public characters
			return $this->listPublicCharacters($limit, $page, $count);
		}

		$userId = (new EncryptionController)->decrypt($user);
		$query = $this->Characters->find('all')
			->where(['Characters.User_Access =' => $userId,
			])
			->limit($limit)
			->page($page);

		if ($query == null) {
			return $this->set('result', []);
		}
		$data = $query->all()->toArray();
		$this->set("result", $data);
	}

	public function get($token) {
	}

}