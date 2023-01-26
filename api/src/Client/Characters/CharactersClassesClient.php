<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersClient;
use App\Error\Exceptions\InputException;
class CharactersClassesClient extends AbstractClient {
	const TABLE = 'CharactersClasses';

	static function create(string $charId, object $class, string $token):string {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			if (!property_exists($class, "name") || $class->name == null) {
				throw new InputException('Class name is required and cannot be blank');
			} else if (trim($class->name) == "") {
				throw new InputException('Class name is required and cannot be blank');
			}

			if (!property_exists($class, "level") || !is_numeric($class->level)) {
				throw new InputException("Class level must be an integer");

			}
			$classItem = parent::fetchTable(CharactersClassesClient::TABLE)->newEntity([
				'Char_ID' => parent::decrypt($charId),
				'Class' => $class->name,
				'Level' => $class->level
			]);
			$result = parent::fetchTable(CharactersClassesClient::TABLE)->save($classItem);
			if ($result != false) {
				return $result->id;
			}
		}
		return "";
	}

	static function read(string $charId, string $token, string $classId) {
		if (CharactersClient::canView(charId: $charId, token: $token)) {
			return parent::fetchTable(CharactersClassesClient::TABLE)->find('all')
			->where(['ID' => parent::decrypt($classId)])
			->first();
		}
		return null;
	}

	static function update(string $charId, object $class, string $token):bool {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			$classItem = parent::fetchTable(CharactersClassesClient::TABLE)->find('all')
			->where(['ID' => parent::decrypt($class->id)])
			->first();
			if ($classItem != null) {
				if (property_exists($class, "name") && $class->name != null) {
					if (trim($class->name) == "") {
						throw new InputException("Class name cannot be blank");
					}
					$classItem->Class = $class->name;
				}

				if (property_exists($class, "level") && $class->level != null) {
					$classItem->Level = $class->level;
				}

				$result = parent::fetchTable(CharactersClassesClient::TABLE)->save($classItem);
				return $result != false;
			}
		}
		return false;
	}

	static function delete(string $charId, string $classId, string $token):bool {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			$class = parent::fetchTable(CharactersClassesClient::TABLE)->find('all')
				->where(['ID' => parent::decrypt($classId)])
				->first();

			if ($class == null) {
				return false;
			}
			if (CharactersClassesClient::classCount(charId: $charId, token: $token) <= 1) {
				throw new LogicException('Characters must have at least one class');
			}
			$result = parent::fetchTable(CharactersClassesClient::TABLE)->delete($class);
			return $result != false;
		}
		return false;
	}

	static function classCount(string $charId, string $token):int {
		return sizeOf(parent::fetchTable(CharactersClassesClient::TABLE)->find('all')
		->where(['Char_ID' => parent::decrypt($charId)])
		->all()
		->toArray());
	}
}