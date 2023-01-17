<?php
namespace App\Schema\Character;
use App\Schema\SchemaInterface;

class CharacterBackgroundSchema implements SchemaInterface {
	static function toSummarizedSchema($background): mixed {
		return [
			'id' => $background->id,
			'name' => $background->Name,
			'description' => $background->Description
		];
	}

	static function toExtendedSchema($background): array {
		return CharacterBackgroundSchema::toSummarizedSchema($background);
	}
}

