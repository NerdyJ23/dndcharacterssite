<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;

class CharactersStatsClient extends AbstractClient {
	static function list(int $charId):array {
		$query = parent::fetchTable('CharactersStats')->find('all')
		->where(['Char_ID' => $char[0]->ID])
		->limit($limit)
		->page($page);

		return $query == null ? null : $query->all()->toArray();
	}

	static function create(int $charId, object $stats):string {
		$statItem = parent::fetchTable('CharactersStats')->newEntity([
			'Char_ID' => $charId,
			'Name' => $stats->name,
			'Value' => $stats->value
		]);

		$result = parent::fetchTable('CharactersStats')->save($statItem);

		if ($result != false) {
			return $result->id;
		}
		return "";
	}

	static function update(object $stat):bool {
		if (!property_exists($stat, 'id') || $stat->id == null) {
			return false;
		}

		$statItem = parent::fetchTable('CharactersStats')->get(parent::decrypt($stat->id));

		if (property_exists($stat, 'name') && $stat->name != null) {
			$statItem->Name = $stat->name;
		}

		if (property_exists($stat, 'value') && $stat->value != null) {
			$statItem->Value = $stat->value;
		}

		$result = parent::fetchTable('CharactersStats')->save($statItem);
		return $result != false;
	}
}