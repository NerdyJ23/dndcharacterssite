<?php
namespace App\Client;

use Cake\ORM\TableRegistry;
use App\Client\Security\EncryptionClient;

class AbstractClient {
	static function fetchTable(string $table) {
		return TableRegistry::getTableLocator()->get($table);
	}

	public function decrypt(string $id):int {
		return EncryptionClient::decrypt($id);
	}

	public function encrypt(int $id):string {
		return EncryptionClient::encrypt($id);
	}
}