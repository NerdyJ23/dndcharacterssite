<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\EventInterface;

class CharactersBackgroundsTable extends Table {
	public function initialize(array $config): void {
		$this->setTable('Characters_Background');
		$this->setEntityClass('CharacterBackground');
		$this->belongsTo('Characters', ['foreignKey' => 'ID']);
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>