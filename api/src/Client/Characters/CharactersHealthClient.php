<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Error\Exceptions\LogicException;
class CharactersHealthClient extends AbstractClient {

	static function create(int $charId, object $health) {
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
	static function read(string $healthId, string $charId) {
		$result = parent::fetchTable('CharactersHealth')
		->find()
		->where(['ID' => parent::decrypt($healthId),
			'Char_ID' => parent::decrypt($charId)
		])
		->first();

		if ($result != false) {
			return $result;
		}
		return null;
	}
	static function update(object $health, int $userId):bool {
		if (property_exists($health, 'id') && $health->id != null) {
			$healthItem = parent::fetchTable('CharactersHealth')->get(parent::decrypt($health->id));

			if (property_exists($health, 'current_health') && $health->current_health != null) {
				$healthItem->Current_Health = $health->current_health;
			}

			if (property_exists($health, 'max_health') && $health->max_health != null) {
				$healthItem->Max_Health = $health->max_health;
			}

			if (property_exists($health, 'temporary_health') && $health->temporary_health != null) {
				$healthItem->Temporary_Health = $healthItem->temporary_health;
			}

			if (property_exists($health, 'hit_die') && $health->hit_die != null) {
				$healthItem->Hit_Die = $health->hit_die;
			}

			if (property_exists($health, 'death_fails') && $health->death_fails != null) {
				$healthItem->Death_Fails = $health->death_fails;
			}

			if (property_exists($health, 'death_success') && $health->death_success != null) {
				$healthItem->Death_Success = $health->death_success;
			}

			if ($healthItem->Max_Health < $healthItem->Current_Health) {
				throw new LogicException('Character Max HP (' . $healthItem->Max_Health . ') cannot be less than Current HP (' . $healthItem->Current_Health .')');
			}
			$result = parent::fetchTable('CharactersHealth')->save($healthItem);
			return $result != false;
		}
		return false;
	}
}