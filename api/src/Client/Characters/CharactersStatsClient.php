<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;

class CharactersStatsClient extends AbstractClient {
	public function list(int $charId):array {
		$query = parent::fetchTable('CharactersStats')->find('all')
		->where(['Char_ID' => $char[0]->ID])
		->limit($limit)
		->page($page);

		return $query == null ? null : $query->all()->toArray();
	}

	public function create(int $charId, object $stats):string {
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
}