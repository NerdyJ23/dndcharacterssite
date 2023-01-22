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
}