<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Http\Response;

use App\Controller\Security\EncryptionController;
use App\Controller\Component\Enum\StatusCodes;

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

	protected function decrypt($id) {
		return (new EncryptionController)->decrypt($id);
	}

    protected function response($enum) {
        $response = new Response();
		try {
			return match($enum) {
				StatusCodes::SUCCESS => $response->withStatus(200),
				StatusCodes::CREATED => $response->withStatus(201),
				StatusCodes::NO_CONTENT => $response->withStatus(204),

				StatusCodes::USER_ERROR => $response->withStatus(400),
				StatusCodes::TOKEN_MISMATCH => $response->withStatus(403, 'Token Mismatch. Clear cookies and try again'),
				StatusCodes::ACCESS_DENIED => $response->withStatus(403),
				StatusCodes::NOT_FOUND => $response->withStatus(404),

				StatusCodes::SERVER_ERROR => $response->withStatus(500),
			};
		} catch (any $err) {
			return $response->withStatus(500);
		}
    }
}

?>