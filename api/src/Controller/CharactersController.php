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

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$user = 1;

		$query = $this->Characters->find('all')
		->where(['Characters.User_Access =' => $user])
		->limit($limit)
		->page($page);
		$data = $query->all()->toArray();
		$this->set("result", $data);
	}

	public function get($token) {
	}

}