<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class CharactersHealthTable extends Table {
	public function initialize(array $config): void {
		// $this->setDisplayField('Class');
		$this->setTable('Characters_Health');
		$this->setEntityClass('CharacterHealth');
		$this->belongsTo('Characters', ['foreignKey' => 'ID']);
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>