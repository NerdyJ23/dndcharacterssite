<?php

namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersClient;

class CharactersBackgroundClient extends AbstractClient{
	const TABLE = "CharactersBackgrounds";

	static function list(int $id, string $token) {
		$char = CharactersClient::get($id, $token);
		if ($char != null) {
			if (property_exists($char, "Background")) {
				return $char->background;
			}
		}
		return null;
	}

	static function create(int $charId, object $background): string {
		$backgroundItem = parent::fetchTable(CharactersBackgroundClient::TABLE)->newEntity([
			'Char_ID' => $charId,
			'Name' => $background->name,
			'Description' => $background->description
		]);
		$result = parent::fetchTable(CharactersBackgroundClient::TABLE)->save($backgroundItem);

		if ($result != null) {
			return $result->id;
		}
		return "";
	}

	static function update(object $background, int $userId):bool {
		$backgroundItem = parent::fetchTable('CharactersBackgrounds')->get(parent::decrypt($background->id));

		if (property_exists($background, 'name') && $background->name != null) {
			$backgroundItem->Name = $background->name;
		}

		if (property_exists($background, 'description') && $background->description != null) {
			$backgroundItem->Description = $background->description;
		}

		$result = parent::fetchTable('CharactersBackgrounds')->save($backgroundItem);
		return $result != false;
	}
}