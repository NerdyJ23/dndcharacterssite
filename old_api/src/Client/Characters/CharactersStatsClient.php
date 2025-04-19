<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersClient;
use App\Controller\Component\Pagination;
use App\Error\Exceptions\InputException;

class CharactersStatsClient extends AbstractClient {
	const TABLE = 'CharactersStats';

	static function list(string $charId, mixed $token, Pagination $pagination){
		if (CharactersClient::canView(token: $token, charId: $charId)) {
			$query = parent::fetchTable(CharactersStatsClient::TABLE)->find('all')
			->where(['Char_ID' => parent::decrypt($charId)]);
			return parent::toList($query, $pagination);
		}
		return (object)['list' => [], 'total' => 0];
	}

	static function create(string $charId, object $stat, mixed $token):string {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			parent::assertKeys($stat, ["name"]);
			parent::assertKeys($stat, ["value"], "number");

			$statItem = parent::fetchTable(CharactersStatsClient::TABLE)->newEntity([
				'Char_ID' => parent::decrypt($charId),
				'Name' => $stat->name,
				'Value' => $stat->value
			]);

			$result = parent::fetchTable(CharactersStatsClient::TABLE)->save($statItem);

			if ($result != false) {
				return $result->id;
			}
		}
		return "";
	}

	static function read(string $charId, string $statId, mixed $token) {
		if (!CharactersClient::canView(token: $token, charId: $charId)) {
			return null;
		}
		return parent::fetchTable(CharactersStatsClient::TABLE)->find('all')
		->where([
			'ID' => parent::decrypt($statId),
			'Char_ID' => parent::decrypt($charId)
		])
		->first();
	}

	static function update(object $stat, mixed $token, string $charId):bool {
		if (!property_exists($stat, 'id') || $stat->id == null) {
			return false;
		}

		$statItem = parent::fetchTable(CharactersStatsClient::TABLE)->find('all')
		->where([
			'ID' => parent::decrypt($stat->id),
			'Char_ID' => parent::decrypt($charId)
		])
		->first();

		if ($statItem == null) {
			return false;
		}

		if (property_exists($stat, 'name') && $stat->name != null) {
			if (trim($stat->name) == "") {
				throw new InputException('Name cannot be empty');
			}
			$statItem->Name = $stat->name;
		}

		if (property_exists($stat, 'value') && $stat->value != null) {
			if (!is_numeric($stat->value)) {
				throw new InputException('Value must be an integer');
			}
			$statItem->Value = $stat->value;
		}

		$result = parent::fetchTable(CharactersStatsClient::TABLE)->save($statItem);
		return $result != false;
	}

	static function delete(string $statId, mixed $token, string $charId):bool {
		if (CharactersClient::canEdit(token: $token, charId: $charId)) {
			$stat = CharactersStatsClient::read(token: $token, charId: $charId, statId: $statId);
			if ($stat != null) {
				$result = parent::fetchTable(CharactersStatsClient::TABLE)->delete($stat);
				return $result != false;
			}
		}
		return false;
	}
}