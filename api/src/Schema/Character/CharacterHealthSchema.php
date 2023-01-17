<?php
namespace App\Schema\Character;
use App\Model\Entity\CharacterHealth;

class CharacterHealthSchema {
	static function toSummarizedSchema(CharacterHealth $health): array {
		return CharacterHealthSchema::toExtendedSchema($health);
	}

	static function toExtendedSchema(CharacterHealth $health): array {
		return [
			'id' => $health->id,
			'current_health' => $health->Current_Health,
			'max_health' => $health->Max_Health,
			'temporary_health' => $health->Temporary_Health,
			'hit_die' => $health->Hit_Die,
			'death_fails' => $health->Death_Fails,
			'death_success' => $health->Death_Success
		];
	}
}

