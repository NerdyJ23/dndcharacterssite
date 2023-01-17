<?php
namespace App\Schema\Character;
use App\Schema\SchemaInterface;

class CharacterBackgroundSchema implements SchemaInterface {
	static function toSummarizedSchema($background): mixed {
		return CharacterBackgroundSchema::toExtendedSchema($background);
	}

	static function toExtendedSchema($background): array {
		return [
			'id' => $background->id,
			'name' => $background->Name,
			'description' => $background->Description
		];
	}
}

