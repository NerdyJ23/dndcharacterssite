<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

use Cake\Utility\Security;
use Cake\View\JsonView;
use Cake\Auth\WeakPasswordHasher;

class ApiController extends Controller {

	public function initialize(): void {
		parent::initialize();
		$this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
	}

	public function index() {
		$this->set("index", "yes");
	}

	public function beforeFilter(EventInterface $event) {
		$this->_returnJSON();
	}

	protected function _returnJSON() {
		$this->response = $this->response->cors($this->request)
		->allowOrigin('*.jessprogramming.com')
		->build();
		$this->response = $this->response->withHeader('Access-Control-Allow-Credentials', 'true');
		$this->viewBuilder()->setOption('serialize',true);

	}

	protected function _isPushingData() {
		return ($this->request->is('post') || $this->request->is('patch'));
	}

	protected function _isListingData() {
		return ($this->request->is('get') || $this->request->is('list'));
	}
}

?>