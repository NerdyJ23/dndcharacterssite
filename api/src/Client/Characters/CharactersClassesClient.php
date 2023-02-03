<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersClient;
use App\Error\Exceptions\InputException;
class CharactersClassesClient extends AbstractClient {
	const TABLE = 'CharactersClasses';

	static function create(string $charId, object $class, mixed $token):string {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			parent::assertKeys($class, ["name"]);
			parent::assertKeys($class, ["level"], "number");

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

	static function read(string $charId, mixed $token, string $classId) {
		if (CharactersClient::canView(charId: $charId, token: $token)) {
			return parent::fetchTable(CharactersClassesClient::TABLE)->find('all')
			->where(['ID' => parent::decrypt($classId)])
			->first();
		}
		return null;
	}

	static function update(string $charId, object $class, mixed $token):bool {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			$classItem = parent::fetchTable(CharactersClassesClient::TABLE)->find('all')
			->where(['ID' => parent::decrypt($class->id)])
			->first();
			if ($classItem != null) {
				if (parent::propertyExists($class, "name")) {
					parent::assertKeys($class, ["name"]);
					$classItem->Class = $class->name;
				}

				if (parent::propertyExists($class, "level")) {
					parent::assertKeys($class, ["level"], "number");
					$classItem->Level = $class->level;
				}

				$result = parent::fetchTable(CharactersClassesClient::TABLE)->save($classItem);
				return $result != false;
			}
		}
		return false;
	}

	static function delete(string $charId, string $classId, mixed $token):bool {
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

	static function classCount(string $charId, mixed $token):int {
		return sizeOf(parent::fetchTable(CharactersClassesClient::TABLE)->find('all')
		->where(['Char_ID' => parent::decrypt($charId)])
		->all()
		->toArray());
	}
}