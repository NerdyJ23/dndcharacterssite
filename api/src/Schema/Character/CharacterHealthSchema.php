<?php
namespace App\Schema\Character;
use App\Model\Entity\CharacterHealth;
use App\Schema\SchemaInterface;

class CharacterHealthSchema implements SchemaInterface {
	static function toSummarizedSchema($health) {
		return [
			'id' => $health->id,
			'current_health' => $health->Current_Health,
			'max_health' => $health->Max_Health,
			'temporary_health' => $health->Temporary_Health,
		];
	}
	static function toExtendedSchema($health) {
		$result = CharacterHealthSchema::toSummarizedSchema($health);
		$result += [
			'hit_die' => $health->Hit_Die,
			'death_fails' => $health->Death_Fails,
			'death_success' => $health->Death_Success
		];
		return $result;
	}
}

