<?php
namespace App\Schema\Character;
use App\Model\Entity\CharacterBackground;

class CharacterBackgroundSchema {
	static function toSummarizedSchema(CharacterBackground $background): array {
		return CharacterBackgroundSchema::toExtendedSchema($background);
	}

	static function toExtendedSchema(CharacterBackground $background): array {
		return [
			'id' => $background->id,
			'name' => $background->Name,
			'description' => $background->Description
		];
	}
}

