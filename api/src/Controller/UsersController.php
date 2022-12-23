<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use Cake\Http\Cookie\Cookie;
use DateTime;

class UsersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function list() {
		$this->set('result', (new AuthenticationController)->validUser('admin','PASSWORD'));
	}

	public function get($token) {
		$query = $this->Users->find('all')
			->where(['Users.token IS ' => $token])
			->limit(1);
		$data = $query->all()->toArray();
		if(sizeof($data) > 0) {
			return $data[0]->id;
		} else {
			return -1;
		}
	}

	public function login() {
		$pass = $this->request->getData('password');
		$username = $this->request->getData('username');
		$validUser = (new AuthenticationController)->validUser('admin',$pass);

		if($validUser == true) {
			$query = $this->Users->find('all')
				->where(['Users.username = ' => $username])
				->limit(1);
			$data = $query->all()->toArray();

			$this->_loginAuth($data[0]);
		} else {
			$this->response = $this->response->withStatus(403, 'Login failed. Check credentials are correct');
		}
	}

	private function _loginAuth($user) {
		$decrypted = intval((new EncryptionController)->decrypt($user->encryptedId));

		$userTable = $this->getTableLocator()->get('Users');
		$u = $userTable->get($user->id);
		$userTable->patchEntity($u, ['token' => (new AuthenticationController)->generateToken()]);

		$u->token = (new AuthenticationController)->generateToken();
		$u->token_valid_until = $u->setTokenTimeLimit(7);
		$result = $userTable->saveOrFail($u);

		$aftersave = $this->Users->get($decrypted);

		if($result != false) {
			// $response = $this->response;
			$cookie = Cookie::create(
				'token',
				$u->token,
				[
					'expires' => new DateTime('+ 7 days'),
					'httpOnly' => false,
					'secure' => true,
					'domain' => 'budget.jessprogramming.com'
			]);
			// $cookie = $cookie
			// 	->withSameSite('None')
			// 	->withSecure(true);
			$this->response = $this->response->withCookie($cookie);
			// $response = $response->withHeader('Set-Cookie', 'accessToken=' . $u->token . '; HttpOnly; Secure; SameSite=Strict; Max-Age=604800;');
			$this->response = $this->response->withStatus(200, 'Logged in successfully');
		} else {
			$this->response = $this->response->withStatus(400);
		}
	}


}