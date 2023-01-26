<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersBackgroundClient;
use App\Client\Characters\CharactersHealthClient;
use App\Client\Characters\CharactersStatsClient;
use App\Client\Characters\CharactersClassesClient;
use App\Client\Security\AuthClient;
use App\Client\Users\UserClient;
use App\Controller\Component\Pagination;
use App\Controller\Component\Enum\SuccessState;

use App\Error\Exceptions\UserNotFoundException;

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

	static function create(object $char, string $token): string {
		if (!property_exists($char, "first_name") && !property_exists($char, "race") && !property_exists($char)) {
			return "";
		}

		$user = UserClient::getByToken($token);
		if ($user == null) {
			throw new UserNotFoundException();
		}

		$charItem = parent::fetchTable('Characters')->newEntity([
			'First_Name' => $char->first_name,
			'Race' => $char->race,
			'User_Access' => $user->ID,
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
				CharactersHealthClient::create(parent::decrypt($result->id), (object)$char->health);
			}

			if (property_exists($char, "stats")) {
				if (is_string($char->stats)) {
					$char->stats = json_decode($char->stats);
				}
				foreach ($char->stats as $stat) {
					CharactersStatsClient::create(parent::decrypt($result->id), $stat);
				}
			}

			if (property_exists($char, "class")) {
				if (is_string($char->class)) {
					$char->class = json_decode($char->class);
				}
				foreach ($char->class as $class) {
					CharactersClassesClient::create(parent::decrypt($result->id), $class);
				}
			}

			if (property_exists($char, "background")) {
				if (is_string($char->background)) {
					$char->background = json_decode($char->background);
				}
				CharactersBackgroundClient::create(parent::decrypt($result->id), $char->background);
			}
			return $result->id;
		}
		return "";
	}

	static function read(string $id, mixed $token) {
		$valid = AuthClient::validToken($token);
		if (!$valid) {
			$query = parent::fetchTable(CharactersClient::TABLE)->find('all')
			->where([
				'Characters.ID =' => parent::decrypt($id),
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
					'Characters.ID =' => parent::decrypt($id),
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

	static function update(object $char, string $token):object {
		$user = UserClient::getByToken($token);
		if ($user == null) {
			throw new UserNotFoundException();
		}
		$charItem = parent::fetchTable(CharactersClient::TABLE)
		->find()
		->where([
			'Characters.ID' => parent::decrypt($char->id),
			'Characters.User_Access' => $user->ID
		])
		->contain(['Health', 'Background'])
		->first();

		$response = (object)[
			'status' => SuccessState::NONE,
			'message' => []
		];

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

		//Stats
		if (property_exists($char, 'stats') && $char->stats != null) {
			$charStats = json_decode($char->stats);
			foreach ($charStats as $stat) {
				if (property_exists($stat, 'id') && $stat->id != null) {
					$result = CharactersStatsClient::update($stat, $token);
					if ($result == false) {
						$response->message[] = 'Character stat ' . $stat->id . ' failed to save';
					}
				} else {
					$result = false;
					$response->message[] = 'Character stat ' . $stat->name . ' has no ID';
				}
				$response->status = SuccessState::success($response->status, $result != false);
			}
		}

		//Health
		if (property_exists($char, 'health') && $char->health != null) {
			$healthItem = json_decode($char->health);
			if (!property_exists($healthItem, 'id') || $healthItem->id == null) {
				$healthItem->id = $charItem->health->id;
			}
			$result = CharactersHealthClient::update(health: $healthItem, token: $token, charId: $char->id);
			if ($result == false) {
				$response->message[] = 'Character health ' . $healthItem->id . ' failed to save';
				$response->status = SuccessState::success($response->status, false);
			}
		}

		//Background
		if (property_exists($char, 'background') && $char->background != null) {
			$backgroundItem = json_decode($char->background);
			if (!property_exists($backgroundItem, 'id') || $backgroundItem->id == null) {
				$backgroundItem->id = $charItem->background->id;
			}
			$result = CharactersBackgroundClient::update(background: $backgroundItem, token: $token, charId: $charItem->id);

			if ($result == false) {
				$response->message[] = 'Character background ' . $backgroundItem->id . ' failed to save';
				$response->status = SuccessState::success($response->status, false);
			}
		}
		//Save Character
		$result = parent::fetchTable(CharactersClient::TABLE)->save($charItem);
		if ($result == false) {
			$response->status = SuccessState::success($response->status, $result);
			$response->message[] = 'Character failed to save';
		} else {
			$response->status = SuccessState::success($response->status, true);
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

	//Permission Checks
	static function canEdit(mixed $token, string $charId):bool {
		if (!AuthClient::validToken($token)) {
			return false;
		}
		$user = UserClient::getByToken($token);
		if ($user == null) {
			throw new UserNotFoundException();
		}
		$char = parent::fetchTable(CharactersClient::TABLE)->find()
		->where([
			'ID' => parent::decrypt($charId),
			'User_Access' => $user->ID
		])
		->first();

		return $char != null;
	}

	static function canView(mixed $token, string $charId):bool {
		return CharactersClient::get($charId, $token) != null;
	}
}