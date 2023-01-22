<?php
namespace App\Client;

use Cake\ORM\TableRegistry;
use App\Controller\Security\EncryptionController;

class AbstractClient {
	public function fetchTable(string $table) {
		return TableRegistry::getTableLocator()->get($table);
	}

	public function decrypt(string $id):int {
		return (new EncryptionController)->decrypt($id);
	}

	public function encrypt(int $id):string {
		return (new EncryptionController)->encrypt($id);
	}
}