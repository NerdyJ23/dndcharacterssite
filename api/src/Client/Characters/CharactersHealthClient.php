<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Error\Exceptions\LogicException;
class CharactersHealthClient extends AbstractClient {

	static function create(string $charId, object $health) {

		$healthItem = parent::fetchTable('CharactersHealth')->newEntity([
			'Char_ID' => parent::decrypt($charId),
			'Current_Health' => $health->current_health
		]);

		if (parent::propertyExists($health, "max_health")) {
			$healthItem->Max_Health = $health->max_health;
		}

		if (parent::propertyExists($health, "temporary_health")) {
			$healthItem->Temporary_Health = $health->temporary_health;
		}

		if (parent::propertyExists($health, "hit_die")) {
			$healthItem->Hit_Die = $health->hit_die;
		}

		if (parent::propertyExists($health, "death_fails")) {
			$healthItem->Death_Fails = $health->death_fails;
		}

		if (parent::propertyExists($health, "death_success")) {
			$healthItem->Death_Success = $health->death_success;
		}

		$result = parent::fetchTable('CharactersHealth')->save($healthItem);

		if ($result != false) {
			return $result->id;
		}
		return "";
	}
	static function read(string $healthId, string $charId, mixed $token) {
		if (!CharactersClient::canView(token: $token, charId: $charId)) {
			return null;
		}
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
	static function update(object $health, mixed $token, string $charId):bool {
		if (!CharactersClient::canEdit(token: $token, charId: $charId)) {
			return false;
		}
		if (parent::propertyExists($health, 'id')) {
			$healthItem = parent::fetchTable('CharactersHealth')->get(parent::decrypt($health->id));

			if (parent::propertyExists($health, 'current_health')) {
				$healthItem->Current_Health = $health->current_health;
			}

			if (parent::propertyExists($health, 'max_health')) {
				$healthItem->Max_Health = $health->max_health;
			}

			if (parent::propertyExists($health, 'temporary_health')) {
				$healthItem->Temporary_Health = $health->temporary_health;
			}

			if (parent::propertyExists($health, 'hit_die')) {
				$healthItem->Hit_Die = $health->hit_die;
			}

			if (parent::propertyExists($health, 'death_fails')) {
				$healthItem->Death_Fails = $health->death_fails;
			}

			if (parent::propertyExists($health, 'death_success')) {
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