<?php
namespace App\Schema\Character;
use App\Model\Entity\CharacterClass;

class CharacterClassSchema {

	static function toSummarizedSchema(CharacterClass $class): array {
		return CharacterClassSchema::toExtendedSchema($class);
	}

	static function toExtendedSchema(CharacterClass $class): array {
		return [
			'id' => $class->id,
			'class' => $class->Class,
			'level' => $class->Level
		];
	}

	static function toListSchema(array $classes) {
		$result = [];
		foreach ($classes as $class) {
			if ($class instanceof CharacterClass) {
				$result[] = CharacterClassSchema::toExtendedSchema($class);
			}
		}
		return $result;
	}
}

