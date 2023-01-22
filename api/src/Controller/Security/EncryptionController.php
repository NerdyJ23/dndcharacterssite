<?php

namespace App\Controller\Security;

use Cake\Controller\Controller;
use App\Client\Security\EncryptionClient;

class EncryptionController extends Controller {

	public function initialize(): void {
		parent::initialize();
	}

	public function encrypt($value) {
		return EncryptionClient::encrypt($value);
	}

	public function decrypt($value) {
		return EncryptionClient::decrypt($value);
	}

	public function hashPassword($pass) {
		return EncryptionClient::hashPassword($pass);
	}
}

?>