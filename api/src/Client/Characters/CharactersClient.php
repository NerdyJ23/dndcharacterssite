<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersBackgroundClient;
use App\Client\Characters\CharactersHealthClient;
use App\Client\Characters\CharactersStatsClient;
use App\Client\Characters\CharactersClassesClient;

class CharactersClient extends AbstractClient {
	public function create(object $char): string {
		$charItem = parent::fetchTable('Characters')->newEntity([
			'First_Name' => $char->first_name,
			'Race' => $char->race,
			'User_Access' => $char->user_access,
		]);

		if (property_exists($char, "nickname") && $char->nickname != null) {
			$charItem->Nickname = $char->nickname;
		}

		if (property_exists($char, "last_name") && $char->last_name != null) {
			$charItem->Last_Name = $char->last_name;
		}

		if (property_exists($char, "exp") && $char->exp != null) {
			$charItem->Exp = $char->exp;
		}

		if (property_exists($char, "alignment") && $char->alignment != null) {
			$charItem->Alignment = $char->alignment;
		}

		if (property_exists($char, "public") && $char->public != null) {
			$charItem->Visibility = $char->public;
		}

		$result = parent::fetchTable('Characters')->save($charItem);

		if ($result != false) {
			if (property_exists($char, "health")) {
				if (is_string($char->health)) {
					$char->health = json_decode($char->health);
				}
				(new CharactersHealthClient)->create($this->decrypt($result->id), (object)$char->health);
			}

			if (property_exists($char, "stats")) {
				if (is_string($char->stats)) {
					$char->stats = json_decode($char->stats);
				}
				foreach ($char->stats as $stat) {
					(new CharactersStatsClient)->create($this->decrypt($result->id), $stat);
				}
			}

			if (property_exists($char, "class")) {
				if (is_string($char->class)) {
					$char->class = json_decode($char->class);
				}
				foreach ($char->class as $class) {
					(new CharactersClassesClient)->create($this->decrypt($result->id), $class);
				}
			}

			if (property_exists($char, "background")) {
				if (is_string($char->background)) {
					$char->background = json_decode($char->background);
				}
				(new CharactersBackgroundClient)->create($this->decrypt($result->id), $char->background);
			}
			return $result->id;
		}
		return "";
	}
}