<?php
namespace App\Schema\Character;

use App\Schema\SchemaInterface;
use App\Schema\AbstractSchema;

class CharacterStatSchema implements SchemaInterface {
	static function toSummarizedSchema($stat): array {
		return [
			'id' => $stat->id,
			'name' => $stat->Name,
			'value' => $stat->Value
		];
	}

	static function toExtendedSchema($stat): array {
		return CharacterStatSchema::toSummarizedSchema($stat);
	}
}

