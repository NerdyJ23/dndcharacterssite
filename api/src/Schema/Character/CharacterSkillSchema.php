<?php
namespace App\Schema\Character;
use App\Model\Entity\CharacterSkill;
use App\Schema\Character\CharacterStatSchema;

class CharacterSkillSchema {
	static function toSummarizedSchema(CharacterSkill $skill): array {
		return CharacterSkillSchema::toExtendedSchema($skill);
	}

	static function toExtendedSchema(CharacterSkill $skill): array {
		return [
			'id' => $skill->id,
			'proficient' => $skill->Proficient,
			'label' => $skill->Label,
			'linked_stat' => CharacterStatSchema::toSummarizedSchema($skill->linked__stat)
		];
	}

	static function toListSchema(array $skills) {
		$result = [];
		foreach ($skills as $skill) {
			if ($skill instanceof CharacterSkill) {
				$result[] = CharacterSkillSchema::toExtendedSchema($skill);
			}
		}
		return $result;
	}
}

