<?php
namespace App\Schema\Character;

use App\Model\Entity\Character;
use App\Schema\AbstractSchema;
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
			'classes' => AbstractSchema::schema($character->classes, 'CharacterClass'),
			'race' => $character->Race,
			'background' => AbstractSchema::schema($character->background, 'CharacterBackground'),
			'alignment' => $character->Alignment,
			'exp' => $character->Exp,
			'public' => $character->Visibility == 1,
		];
	}

	static function toExtendedSchema(Character $character): array {
		$result = CharacterSchema::toSummarizedSchema($character);
		$result += [
			'first_name' => $character->First_Name,
			'last_name' => $character->Last_Name,
			'stats' => AbstractSchema::schema($character->stats, 'CharacterStat'),
			'health' => AbstractSchema::schema($character->health, 'CharacterHealth'),
			'skills' => AbstractSchema::schema($character->skills, 'CharacterSkill')
		];
		return $result;
	}
}

