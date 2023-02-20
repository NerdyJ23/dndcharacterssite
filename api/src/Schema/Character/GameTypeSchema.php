<?php
namespace App\Schema\Character;

use App\Schema\AbstractSchema;
use App\Schema\SchemaInterface;
use App\Controller\Component\Enum\GameType;

class GameTypeSchema implements SchemaInterface{
	static function toSummarizedSchema($gameId): array {
		return [
			'id' => GameType::getValue($gameId)->value,
			'name' => GameType::getName($gameId)
		];
	}

	static function toExtendedSchema($gameId): array {
		return GameTypeSchema::toSummarizedSchema($gameId);
	}
}

