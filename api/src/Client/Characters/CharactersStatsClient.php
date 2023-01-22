<?php
namespace App\Client\Characters;

use App\Client\AbstractClient;

class CharactersStatsClient extends AbstractClient {
	public function create(int $charId, object $stats) {
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