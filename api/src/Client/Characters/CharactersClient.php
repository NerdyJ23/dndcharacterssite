<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersBackgroundClient;
use App\Client\Characters\CharactersHealthClient;
use App\Client\Characters\CharactersStatsClient;
use App\Client\Characters\CharactersClassesClient;
use App\Client\Security\AuthClient;
use App\Client\Security\EncryptionClient;
use App\Client\Users\UserClient;
use App\Controller\Component\Pagination;
use App\Controller\Component\Enum\SuccessState;

class CharactersClient extends AbstractClient {
	const TABLE = "Characters";

	static function listPublic(Pagination $pagination): array {
		return parent::fetchTable('Characters')->find('all')
		->where(['Characters.Visibility = 1'])
		->contain(['Classes'])
		->limit($pagination->getLimit())
		->page($pagination->getPage())
		->all()
		->toArray();
	}

	static function list(Pagination $pagination, string $token):array {
		$user = UserClient::getByToken($token);
		if ($user == null) {
			return UserClient::listPublic($pagination);
		}

		return parent::fetchTable('Characters')->find('all')
		->where(['Characters.User_Access' => $user->ID])
		->contain(['Classes'])
		->limit($pagination->getLimit())
		->page($pagination->getPage())
		->all()
		->toArray();
	}

	static function create(object $char, int $userId): string {
		if (!property_exists($char, "first_name") && !property_exists($char, "race") && !property_exists($char)) {
			return "";
		}

		$charItem = parent::fetchTable('Characters')->newEntity([
			'First_Name' => $char->first_name,
			'Race' => $char->race,
			'User_Access' => $userId,
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
				CharactersHealthClient::create(EncryptionClient::decrypt($result->id), (object)$char->health);
			}

			if (property_exists($char, "stats")) {
				if (is_string($char->stats)) {
					$char->stats = json_decode($char->stats);
				}
				foreach ($char->stats as $stat) {
					CharactersStatsClient::create(EncryptionClient::decrypt($result->id), $stat);
				}
			}

			if (property_exists($char, "class")) {
				if (is_string($char->class)) {
					$char->class = json_decode($char->class);
				}
				foreach ($char->class as $class) {
					CharactersClassesClient::create(EncryptionClient::decrypt($result->id), $class);
				}
			}

			if (property_exists($char, "background")) {
				if (is_string($char->background)) {
					$char->background = json_decode($char->background);
				}
				CharactersBackgroundClient::create(EncryptionClient::decrypt($result->id), $char->background);
			}
			return $result->id;
		}
		return "";
	}

	static function get(int $id, string $token) {
		$valid = AuthClient::validToken($token);
		if (!$valid) {
			$query = parent::fetchTable(CharactersClient::TABLE)->find('all')
			->where([
				'Characters.ID =' => $id,
				'Visibility = 1'
			])
			->contain(['Classes', 'Stats', 'Health', 'Background', 'Skills', 'Skills.Linked_Stat']);
			$result = $query->all()->toArray();
			return sizeOf($result) == 0 ? null : $result[0];
		} else {
			$user = UserClient::getByToken($token);
			if ($user == null) {
				return StatusCodes::TOKEN_MISMATCH;
			}

			$query = parent::fetchTable(CharactersClient::TABLE)->find('all')
				->where([
					'Characters.ID =' => $id,
					'OR' => [
						['Characters.Visibility = 1'],
						['Characters.User_Access =' => $user->ID]
					]
			])
			->contain(['Classes', 'Stats', 'Health', 'Background', 'Skills', 'Skills.Linked_Stat']);
			$result = $query->all()->toArray();
			return sizeOf($result) == 0 ? null : $result[0];
		}
	}

	static function update(object $char, int $userId):SuccessState {
		$charItem = parent::fetchTable(CharactersClient::TABLE)->get(EncryptionClient::decrypt($char->id));
		$response = SuccessState::NONE;

		if (property_exists($char, 'first_name') && $char->first_name != null) {
			$charItem->First_Name = $char->first_name;
		}

		if (property_exists($char, 'nickname') && $char->nickname != null) {
			$charItem->Nickname = $char->nickname;
		}

		if (property_exists($char, 'last_name') && $char->last_name != null) {
			$charItem->Last_Name = $char->last_name;
		}

		if (property_exists($char, 'race') && $char->race != null) {
			$charItem->Race = $char->race;
		}

		if (property_exists($char, 'exp') && $char->exp != null) {
			$charItem->Exp = $char->exp;
		}

		if (property_exists($char, 'alignment') && $char->alignment != null) {
			$charItem->Alignment = $char->alignment;
		}

		if (property_exists($char, 'public') && $char->public != null) {
			$charItem->Visibility = $char->public;
		}

		if (property_exists($char, 'stats') && $char->stats != null) {
			$charStats = json_decode($char->stats);
			foreach ($charStats as $stat) {
				$result = CharactersStatsClient::update($stat, $userId);
				$response = SuccessState::success($response, $result);
			}
		}
		$result = parent::fetchTable(CharactersClient::TABLE)->save($charItem);
		if ($result == false) {
			$response = SuccessState::success($response, $result);
		} else {
			$response = SuccessState::success($response, true);
		}
		return $response;
	}

	static function uploadImage($file, $charId):bool {
		if ($file == null) {
			return false;
		}
		try {
			$file->moveTo(CharactersClient::getFilePath($charId));
		} catch(exception $e) {
			return false;
		}
		return true;
	}

	static function getFilePath($id) {
		return RESOURCES . 'portraits' . DS . $id . '.png';
	}
}