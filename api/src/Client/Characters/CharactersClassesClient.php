<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersClient;

class CharactersClassesClient extends AbstractClient {
	const TABLE = 'CharactersClasses';
	static function create(string $charId, object $class, string $token):string {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
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

	static function update(string $charId, object $class, string $token):bool {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			$classItem = parent::fetchTable(CharactersClassesClient::TABLE)->get(parent::decrypt($class->id));
			if ($classItem != null) {
				if (property_exists($class, "name") && $class->name != null) {
					if (trim($class->name) != "") {
						$classItem->Class = $class->name;
					}
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

}