<?php
namespace App\Schema\Character;

use App\Schema\AbstractSchema;
use App\Model\Entity\CharacterStat;

class CharacterStatSchema {
	static function toSummarizedSchema(CharacterStat $stat): array {
		return CharacterStatSchema::toExtendedSchema($stat);
	}

	static function toExtendedSchema(CharacterStat $stat): array {
		return [
			'id' => $stat->id,
			'name' => $stat->Name,
			'value' => $stat->Value
		];
	}

	static function toListSchema(array $stats) {
		$result = [];
		foreach ($stats as $stat) {
			if ($stat instanceof CharacterStat) {
				$result[] = CharacterStatSchema::toExtendedSchema($stat);
			}
		}
		return $result;
	}
}

