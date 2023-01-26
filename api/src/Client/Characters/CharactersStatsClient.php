<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;
use App\Client\Characters\CharactersClient;
use App\Controller\Component\Pagination;

class CharactersStatsClient extends AbstractClient {
	const TABLE = 'CharactersStats';

	static function list(string $charId, mixed $token, Pagination $pagination):array {
		if (CharactersClient::canView(token: $token, charId: $charId)) {
			return parent::fetchTable(CharactersStatsClient::TABLE)->find('all')
			->where(['Char_ID' => parent::decrypt($charId)])
			->page($pagination->getPage())
			->limit($pagination->getLimit())
			->all()
			->toArray();
		}
		return [];
	}

	static function create(int $charId, object $stats):string {
		$statItem = parent::fetchTable(CharactersStatsClient::TABLE)->newEntity([
			'Char_ID' => $charId,
			'Name' => $stats->name,
			'Value' => $stats->value
		]);

		$result = parent::fetchTable(CharactersStatsClient::TABLE)->save($statItem);

		if ($result != false) {
			return $result->id;
		}
		return "";
	}

	static function update(object $stat):bool {
		if (!property_exists($stat, 'id') || $stat->id == null) {
			return false;
		}

		$statItem = parent::fetchTable(CharactersStatsClient::TABLE)->get(parent::decrypt($stat->id));

		if (property_exists($stat, 'name') && $stat->name != null) {
			$statItem->Name = $stat->name;
		}

		if (property_exists($stat, 'value') && $stat->value != null) {
			$statItem->Value = $stat->value;
		}

		$result = parent::fetchTable(CharactersStatsClient::TABLE)->save($statItem);
		return $result != false;
	}
}