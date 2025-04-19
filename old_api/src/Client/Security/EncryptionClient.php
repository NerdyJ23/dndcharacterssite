<?php
namespace App\Client\Security;

use App\Client\AbstractClient;
use Cake\Utility\Security;

class EncryptionClient {
	const METHOD = 'aes128';

	static function encrypt($value) {
		return base64_encode(openssl_encrypt($value, EncryptionClient::METHOD, $_ENV['SECURITY_SALT'], $options=0,  $_ENV['SECURITY_SALT']));
	}

	static function decrypt($value) {
		return openssl_decrypt(base64_decode($value), EncryptionClient::METHOD, $_ENV['SECURITY_SALT'], $options=0, $_ENV['SECURITY_SALT']);
	}

	static function hashPassword($pass) {
		return Security::hash($pass, null, true);
	}
}