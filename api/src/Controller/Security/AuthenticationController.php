<?php
namespace App\Controller\Security;

use Cake\Controller\Controller;
use App\Client\Security\AuthClient;
class AuthenticationController extends Controller {

	public function initialize(): void {
		parent::initialize();
	}
	public function generateToken(): string {
		return AuthClient::generateToken();
	}

	public function validToken($token): bool {
		return AuthClient::validToken($token);
	}
}
?>