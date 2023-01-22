<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
class CharactersClassesClient extends AbstractClient {

	public function create(int $charId, object $class): string {
		$classItem = parent::fetchTable('CharactersClasses')->newEntity([
			'Char_ID' => $charId,
			'Class' => $class->name,
			'Level' => $class->level
		]);
		$result = parent::fetchTable('CharactersClasses')->save($classItem);
		if ($result != false) {
			return $result->id;
		}
		return "";
	}
}