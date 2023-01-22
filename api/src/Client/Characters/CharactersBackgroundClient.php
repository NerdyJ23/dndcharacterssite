<?php

namespace App\Client\Characters;

use App\Client\AbstractClient;

class CharactersBackgroundClient extends AbstractClient{
	public function create(int $charId, object $background): string {
		$backgroundItem = parent::fetchTable('CharactersBackgrounds')->newEntity([
			'Char_ID' => $charId,
			'Name' => $background->name,
			'Description' => $background->description
		]);
		$result = parent::fetchTable('CharactersBackgrounds')->save($backgroundItem);

		if ($result != null) {
			return $result->id;
		}
		return "";
	}
}