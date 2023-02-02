<?php
namespace App\Client;

use Cake\ORM\TableRegistry;
use App\Client\Security\EncryptionClient;
use App\Error\Exceptions\InputException;

class AbstractClient {
	static function fetchTable(string $table) {
		return TableRegistry::getTableLocator()->get($table);
	}

	static function decrypt(string $id):int {
		return EncryptionClient::decrypt($id);
	}

	static function encrypt(int $id):string {
		return EncryptionClient::encrypt($id);
	}

	//Ensure key exists else throw
	static function assertKeys(mixed $obj, array $key, string $type = "string"): void {
		if ($obj == null) {
			throw new InputException("Object is empty or null");
		}
		foreach ($key as $item) {
			if (property_exists($obj, $item) && $obj->$item != null) {
				if ($type == "string") {
					if ($obj->$item == "") {
						throw new InputException($item . " cannot be empty");
					}
				}
			} else {
				throw new InputException($item . " cannot be empty");
			}
		}
	}

	//Check if property is valid to use and return bool
	static function propertyExists(mixed $obj, string $key, string $type = "string"): bool {
		if ($obj == null) {
			return false;
		}

		if (property_exists($obj, $key) && $obj->$key != null) {
			switch ($type) {
				case "string":
					return trim($obj->$key) != "";
				case "array":
					return sizeOf($obj->$key) != 0;
				default:
					return trim($obj->$key) != "";
			}
		}
		return false;
	}
}