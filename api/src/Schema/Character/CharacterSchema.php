<?php
namespace App\Schema\Character;

use App\Model\Entity\Character;
use App\Schema\Character\CharacterHealthSchema;
use App\Schema\Character\CharacterBackgroundSchema;
use App\Schema\Character\CharacterClassSchema;
use App\Schema\Character\CharacterStatSchema;
use App\Schema\Character\CharacterSkillSchema;

class CharacterSchema {
	static function toSummarizedSchema(Character $character): array {
		return [
			'id' => $character->id,
			'full_name' => $character->Full_Name,
			'classes' => CharacterClassSchema::toListSchema($character->classes),
			'race' => $character->Race,
			'background' => CharacterBackgroundSchema::toSummarizedSchema($character->background),
			'alignment' => $character->Alignment,
			'exp' => $character->Exp,
			'public' => $character->Visibility == 1,
		];
	}

	static function toExtendedSchema(Character $character): array {
		return [
			'id' => $character->id,
			'first_name' => $character->First_Name,
			'last_name' => $character->Last_Name,
			'full_name' => $character->Full_Name,
			'race' => $character->Race,
			'exp' => $character->Exp,
			'background' => CharacterBackgroundSchema::toSummarizedSchema($character->background),
			'alignment' => $character->Alignment,
			'public' => $character->Visibility == 1,
			'classes' => CharacterClassSchema::toListSchema($character->classes),
			'stats' => CharacterStatSchema::toListSchema($character->stats),
			'health' => CharacterHealthSchema::toSummarizedSchema($character->health),
			'skills' => CharacterSkillSchema::toListSchema($character->skills)
		];
	}
}

