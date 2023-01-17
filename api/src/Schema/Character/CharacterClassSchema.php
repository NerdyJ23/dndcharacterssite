<?php
namespace App\Schema\Character;
use App\Model\Entity\CharacterClass;
use App\Schema\SchemaInterface;

class CharacterClassSchema implements SchemaInterface {

	static function schema($class): mixed {
		if ($class instanceof CharacterClass) {
			return CharacterClassSchema::toExtendedSchema($class);
		} else if (is_array($class)) {
			$result = [];
			foreach ($class as $c) {
				if ($c instanceof CharacterClass) {
					$result[] = CharacterClassSchema::toSummarizedSchema($c);
				}
			}
			return $result;
		}
		return null;
	}

	static function toSummarizedSchema($class): mixed {
		return CharacterClassSchema::toExtendedSchema($class);
	}

	static function toExtendedSchema($class): array {
		return [
			'id' => $class->id,
			'class' => $class->Class,
			'level' => $class->Level
		];
	}
}

