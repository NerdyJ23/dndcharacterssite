<?php
namespace App\Schema\Character;

use App\Schema\AbstractSchema;
use App\Schema\SchemaInterface;
class CharacterSchema implements SchemaInterface{
	static function toSummarizedSchema($character): array {
		return [
			'id' => $character->id,
			'full_name' => $character->Full_Name,
			'classes' => AbstractSchema::schema($character->classes, 'CharacterClass'),
			'race' => $character->Race,
			'background' => AbstractSchema::schema($character->background, 'CharacterBackground'),
			'alignment' => $character->Alignment,
			'exp' => $character->Exp,
			'public' => $character->Visibility == 1,
			'canEdit' => $character->canEdit
		];
	}

	static function toExtendedSchema($character): array {
		$result = CharacterSchema::toSummarizedSchema($character);
		$result += [
			'first_name' => $character->First_Name,
			'nickname' => $character->Nickname,
			'last_name' => $character->Last_Name,
			'stats' => AbstractSchema::schema($character->stats, 'CharacterStat'),
			'health' => AbstractSchema::schema($character->health, 'CharacterHealth'),
			'skills' => AbstractSchema::schema($character->skills, 'CharacterSkill')
		];
		return $result;
	}
}

