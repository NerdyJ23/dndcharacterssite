<?php
namespace App\Controller;

use DateTime;
use Cake\Controller\Controller;
use Cake\Http\Cookie\Cookie;
use Cake\I18n\FrozenTime;

use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use App\Controller\Component\Enum\StatusCodes;


class UsersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function list() {
		$this->set('result', (new AuthenticationController)->validUser('admin','PASSWORD'));
	}

	public function create() {
		return $this->response(StatusCodes::ACCESS_DENIED);

		$pass = $this->request->getData('password');
		$username = $this->request->getData('username');
		$first_name = $this->request->getData('firstName');
		$last_name = $this->request->getData('lastName');

		$user = $this->fetchTable('Users')->newEntity([
			'First_Name' => $first_name,
			'Last_Name' => $last_name,
			'Username' => $username,
			'Password' => (new EncryptionController)->hashPassword($pass),
			'Created_At' => FrozenTime::now()
		]);

		$result = $this->getTableLocator()->get('Users')->save($user);
		$this->set('result', $result);
	}

	public function get() {
		$token = $this->request->getCookie('token');
		$valid = (new AuthenticationController)->validToken($token);
		if ($valid == true) {
			$query = $this->Users->find('all')
				->where(['Users.Token = ' => $token])
				->limit(1);
			$data = $query->all()->toArray();
			if(sizeof($data) > 0) {
				$this->set('user', $this->toExtendedSchema($data[0]));
				return;
			} else {
				return;
			}
		} else {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
	}

	public function getByToken($token) {
		if ($token == null) {
			return null;
		}
		$valid = (new AuthenticationController)->validToken($token);

		if (!$valid) {
			return null;
		}

		$query = $this->Users->find('all')
			->where(['Users.Token = ' => $token])
			->limit(1);

		if ($query == null) {
			return null;
		}

		$data = $query->all()->toArray();
		return sizeOf($data) == 0 ? null : $data[0];

	}
	public function login() {
		$pass = $this->request->getData('password');
		$username = $this->request->getData('username');
		$validUser = (new AuthenticationController)->validUser($username, $pass);
		if($validUser == true) {
			$query = $this->Users->find('all')
				->where(['Users.Username = ' => $username])
				->limit(1);
			$data = $query->all()->toArray();

			return $this->_loginAuth($data[0]);
		} else {
			$this->response = $this->response->withStatus(403, 'Login failed. Check credentials are correct');
		}
	}

	private function _loginAuth($user) {
		$decrypted = intval((new EncryptionController)->decrypt($user->encryptedId));
		$userTable = $this->getTableLocator()->get('Users');
		$u = $userTable->get($decrypted);
		$token = (new AuthenticationController)->generateToken();
		$userTable->patchEntity($u, ['Token' => $token]);

		$u->token = $token;
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
					'httpOnly' => true,
					'secure' => true,
					'domain' => 'dev-dnd.jessprogramming.com'
			]);
			$cookie = $cookie
				->withSameSite('None');
			// 	->withSecure(true);
			$this->response = $this->response->withCookie($cookie);
			// $response = $response->withHeader('Set-Cookie', 'accessToken=' . $u->token . '; HttpOnly; Secure; SameSite=Strict; Max-Age=604800;');
			// $response = $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:1234');
			$this->response = $this->response->withStatus(200, 'Logged in successfully');
			return;
		} else {
			return $this->response(StatusCodes::USER_ERROR);
		}
	}

	private function toExtendedSchema($user) {
		return [
			'username' => $user->Username,
			'first_name' => $user->First_Name,
			'last_name' => $user->Last_Name
		];
	}
}