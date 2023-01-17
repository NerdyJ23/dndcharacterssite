<?php
namespace App\Schema\Character;
use App\Schema\SchemaInterface;

class CharacterClassSchema implements SchemaInterface {
	static function toSummarizedSchema($class): mixed {
		return [
			'id' => $class->id,
			'class' => $class->Class,
			'level' => $class->Level
		];
	}

	return CharacterClassSchema::toSummarizedSchema($class);
	static function toExtendedSchema($class): array {
	}
}

