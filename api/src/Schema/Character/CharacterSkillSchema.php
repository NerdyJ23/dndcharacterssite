<?php
namespace App\Schema\Character;
use App\Schema\SchemaInterface;
use App\Schema\AbstractSchema;

class CharacterSkillSchema implements SchemaInterface {
	static function toSummarizedSchema($skill): array {
		return CharacterSkillSchema::toExtendedSchema($skill);
	}

	static function toExtendedSchema($skill): array {
		return [
			'id' => $skill->id,
			'proficient' => $skill->Proficient,
			'label' => $skill->Label,
			'linked_stat' => AbstractSchema::schema($skill->linked__stat, 'CharacterStat')
		];
	}
}

