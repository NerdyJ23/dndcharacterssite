<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
class CharactersHealthClient extends AbstractClient {

	public function create(int $charId, object $health) {
		$healthItem = parent::fetchTable('CharactersHealth')->newEntity([
			'Char_ID' => $charId,
			'Current_Health' => $health->current_health
		]);

		if (property_exists($health, "max_health")) {
			$healthItem->Max_Health = $health->max_health;
		}

		if (property_exists($health, "temporary_health")) {
			$healthItem->Temporary_Health = $health->temporary_health;
		}

		if (property_exists($health, "hit_die")) {
			$healthItem->Hit_Die = $health->hit_die;
		}

		if (property_exists($health, "death_fails")) {
			$healthItem->Death_Fails = $health->death_fails;
		}

		if (property_exists($health, "death_success")) {
			$healthItem->Death_Success = $health->death_success;
		}

		$result = parent::fetchTable('CharactersHealth')->save($healthItem);

		if ($result != false) {
			return $result->id;
		}
		return "";
	}
}