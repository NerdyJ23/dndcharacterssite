<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class CharactersStatsTable extends Table {
	public function initialize(array $config): void {
		// $this->setDisplayField('Class');
		$this->setTable('Characters_Stats');
		$this->setEntityClass('CharacterStat');
		$this->belongsTo('Characters', ['foreignKey' => 'ID']);
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>