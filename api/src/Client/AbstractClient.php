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
				if (!AbstractClient::_isValid($obj->$item, $type)) {
					$message = " cannot be empty";
					if ($type == "number") {
						$message = " must be an integer";
					}
					throw new InputException($item . $message);
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
			return AbstractClient::_isValid($obj->$key, $type);
		}
		return false;
	}

	static function _isValid(mixed $item, string $type):bool {
		switch ($type) {
			case "string":
				return is_string($item) && trim($item) != "";
			case "array":
				return is_array($item) && sizeOf($item) != 0;
			case "number":
				return is_numeric($item);
			case "boolean":
				return is_bool($item);
			case "not_empty":
				return sizeOf((array)$item) != 0;
			default:
				return $item != null;
		}
	}

	static function toObject(mixed $item, string $key): object {
		if ($item != null) {
			if (property_exists($item, $key)) {
				if (is_string($item->$key)) {
					return (object)json_decode($item->$key);
				}
				return (object)$item->$key;
			}
		}
		throw new InputException($key . " is not valid");
	}
}