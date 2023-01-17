<?php
namespace App\Schema\Character;
use App\Schema\SchemaInterface;

class CharacterClassSchema implements SchemaInterface {
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

