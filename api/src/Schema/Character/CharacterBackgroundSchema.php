<?php
namespace App\Schema\Character;
use App\Model\Entity\CharacterBackground;
use App\Schema\SchemaInterface;

class CharacterBackgroundSchema implements SchemaInterface {
	static function schema($background): mixed {
		if ($background == null) {
			return null;
		} else if ($background instanceof CharacterBackground) {
			return CharacterBackgroundSchema::toExtendedSchema($background);
		} else if (is_array($background)) {
			$result = [];
			foreach ($background as $b) {
				if ($b instanceof CharacterBackground) {
					$result[] = CharacterBackgroundSchema::toSummarizedSchema($b);
				}
			}
			return $result;
		}
		return [];
	}
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

