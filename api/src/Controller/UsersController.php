<?php
namespace App\Controller;

use DateTime;
use Cake\Controller\Controller;
use Cake\Http\Cookie\Cookie;
use Cake\I18n\FrozenTime;

use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use App\Controller\Component\Enum\StatusCodes;
use App\Client\Users\UserClient;

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
		$result = UserClient::getByToken($token);
		if ($result != null) {
			$this->set('user', $this->toExtendedSchema($result));
		} else {
			return $this->response(StatusCodes::ACCESS_DENIED);
		}
	}

	public function login() {
		$pass = $this->request->getData('password');
		$username = $this->request->getData('username');
		$token = UserClient::login($username, $pass);
		if ($token != null) {
			$cookie = Cookie::create(
				'token',
				$token,
				[
					'expires' => new DateTime('+ 7 days'),
					'httpOnly' => true,
					'secure' => true,
					'domain' => 'dnd.jessica-moolenschot.dev'
			]);
			$cookie = $cookie
				->withSameSite('None');
			// 	->withSecure(true);
			$this->response = $this->response->withCookie($cookie);
			// $response = $response->withHeader('Set-Cookie', 'accessToken=' . $u->token . '; HttpOnly; Secure; SameSite=Strict; Max-Age=604800;');
			// $response = $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:1234');
			$this->response = $this->response->withStatus(200, 'Logged in successfully');
		} else {
			$this->response = $this->response->withStatus(403, 'Login failed. Check credentials are correct');
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