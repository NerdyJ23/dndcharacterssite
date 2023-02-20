<?php
namespace App\Client\Characters;
use Cake\Filesystem\File;

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

	static function listPublic(Pagination $pagination) {
		$query = parent::fetchTable('Characters')->find('all')
		->where(['Characters.Visibility = 1'])
		->contain(['Classes', 'Background']);
		return parent::toList($query, $pagination);
	}

	static function list(Pagination $pagination, mixed $token) {
		$user = UserClient::getByToken($token);
		if ($user == null) {
			return CharactersClient::listPublic($pagination);
		}

		$query = parent::fetchTable('Characters')->find('all')
		->where(['Characters.User_Access' => $user->ID])
		->contain(['Classes', 'Background']);

		return parent::toList($query, $pagination);
	}

	static function create(object $char, mixed $token): string {
		parent::assertKeys($char, ["first_name", "race"]);
		$char->stats = parent::toObject($char, "stats");
		$char->class = parent::toObject($char, "classes");

		parent::assertKeys($char, ["stats", "class"], "not_empty");
		$user = UserClient::getByToken($token);
		if ($user == null) {
			throw new UserNotFoundException();
		}

		$charItem = parent::fetchTable('Characters')->newEntity([
			'First_Name' => $char->first_name,
			'Race' => $char->race,
			'User_Access' => $user->ID,
		]);

		if (parent::propertyExists($char, "nickname")) {
			$charItem->Nickname = $char->nickname;
		}

		if (parent::propertyExists($char, "last_name")) {
			$charItem->Last_Name = $char->last_name;
		}

		if (parent::propertyExists($char, "exp")) {
			$charItem->Exp = $char->exp;
		}

		if (parent::propertyExists($char, "alignment")) {
			$charItem->Alignment = $char->alignment;
		}

		if (parent::propertyExists($char, "public", "boolean")) {
			$charItem->Visibility = $char->public;
		}

		$result = parent::fetchTable('Characters')->save($charItem);

		if ($result != false) {
			if (parent::propertyExists($char, "health") || parent::propertyExists($char, "health", "array")) {
				CharactersHealthClient::create($result->id, parent::toObject($char, "health"), $token);
			}

			foreach ($char->stats as $stat) {
				CharactersStatsClient::create($result->id, (object)$stat, $token);
			}

			foreach ($char->class as $class) {
				CharactersClassesClient::create($result->id, (object)$class, $token);
			}

			if (parent::propertyExists($char, "background") || parent::propertyExists($char, "background", "array")) {
				$char->background = parent::toObject($char, "background");
				CharactersBackgroundClient::create($result->id, $char->background, $token);
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

			$char = parent::fetchTable(CharactersClient::TABLE)->find('all')
				->where([
					'Characters.ID =' => parent::decrypt($id),
					'OR' => [
						['Characters.Visibility' => 1],
						['Characters.User_Access' => $user->ID]
					]
			])
			->contain(['Classes', 'Stats', 'Health', 'Background', 'Skills', 'Skills.Linked_Stat'])
			->first();

			if ($char != null) {
				$char->canEdit = ($user->ID == $char->User_Access);
			}
			return $char;
		}
	}

	static function update(object $char, mixed $token):object {
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

		if (parent::propertyExists($char, 'first_name')) {
			$charItem->First_Name = $char->first_name;
		}

		if (parent::propertyExists($char, 'nickname')) {
			$charItem->Nickname = $char->nickname;
		}

		if (parent::propertyExists($char, 'last_name')) {
			$charItem->Last_Name = $char->last_name;
		}

		if (parent::propertyExists($char, 'race')) {
			$charItem->Race = $char->race;
		}

		if (parent::propertyExists($char, 'exp')) {
			$charItem->Exp = $char->exp;
		}

		if (parent::propertyExists($char, 'alignment')) {
			$charItem->Alignment = $char->alignment;
		}
		if (property_exists($char, "public")) {
			$charItem->Visibility = (int)$char->public;
		}

		//Stats
		if (property_exists($char, 'stats')) {
			$charStats = parent::toObject($char, "stats");
			foreach ($charStats as $stat) {
				$stat = (object)$stat;
				if (parent::propertyExists($stat, 'id')) {
					$result = CharactersStatsClient::update($stat, $token, $char->id);
				} else {
					$result = CharactersStatsClient::create(charId: $char->id, stat: $stat, token: $token);
				}
				if ($result == false) {
					$response->message[] = 'Character stat ' . $stat->id . ' failed to save';
				}
				$response->status = SuccessState::success($response->status, $result != false);
			}
		}

		//Health
		if (property_exists($char, 'health')) {
			$healthItem = parent::toObject($char, "health");
			if (!parent::propertyExists($healthItem, 'id')) {
				$healthItem->id = $charItem->health->id;
			}
			$result = CharactersHealthClient::update(health: $healthItem, token: $token, charId: $char->id);
			if ($result == false) {
				$response->message[] = 'Character health ' . $healthItem->id . ' failed to save';
				$response->status = SuccessState::success($response->status, false);
			}
		}

		//Background
		if (property_exists($char, 'background')) {
			$backgroundItem = parent::toObject($char, "background");
			if (!parent::propertyExists($backgroundItem, 'id')) {
				$backgroundItem->id = $charItem->background->id;
			}
			$result = CharactersBackgroundClient::update(background: $backgroundItem, token: $token, charId: $charItem->id);

			if ($result == false) {
				$response->message[] = 'Character background ' . $backgroundItem->id . ' failed to save';
				$response->status = SuccessState::success($response->status, false);
			}
		}

		if (property_exists($char, 'classes')) {
			$classes = parent::toObject($char, "classes");
			foreach($classes as $class) {
				$class = (object)$class;
				if (parent::propertyExists($class, "id")) {
					CharactersClassesClient::update(class: $class, token: $token, charId: $char->id);
				} else {
					CharactersClassesClient::create(class: $class, token: $token, charId: $char->id);
				}
			}
		}
		//Delete items
		if (property_exists($char, "toDelete")) {
			$delete = parent::toObject($char, "toDelete");
			if (property_exists($delete, "stats")) {
				$deleteStats = parent::toObject($delete, "stats");
				foreach ($deleteStats as $stat) {
					$stat = (object)$stat;
					if (parent::propertyExists($stat, "id")) {
						CharactersStatsClient::delete(statId: $stat->id, token: $token, charId: $char->id);
					}
				}
			}

			if (property_exists($delete, "classes")) {
				$deleteClass = parent::toObject($delete, "classes");
				foreach ($deleteClass as $class) {
					$class = (object)$class;
					if (parent::propertyExists($class, "id")) {
						CharactersClassesClient::delete(classId: $class->id, token: $token, charId: $char->id);
					}
				}
			}

			if (property_exists($delete, "profile")) {
				CharactersClient::removeCharacterImage(charId: $char->id, token: $token);
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

	static function removeCharacterImage(string $charId, string $token) {
		if (!CharactersClient::canEdit(charId: $charId, token: $token)) {
			return false;
		}

		try {
			$files = new File(CharactersClient::getFilePath($charId));
			if ($files == null) {
				return false;
			}

			$result = $files->delete();
			return $result;
		} catch (exception $e) {
			return false;
		}
	}

	static function getFilePath(string $id) {
		return RESOURCES . 'portraits' . DS . parent::decrypt($id) . '.png';
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
		return CharactersClient::read($charId, $token) != null;
	}
}