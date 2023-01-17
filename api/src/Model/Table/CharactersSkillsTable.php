<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class CharactersSkillsTable extends Table {
	public function initialize(array $config): void {
		// $this->setDisplayField('Class');
		$this->setTable('Characters_Abilities');
		$this->setEntityClass('CharacterSkill');
		$this->belongsTo('Characters', ['foreignKey' => 'ID']);

		$this->hasOne('Linked_Stat', ['className' => 'CharactersStats'])
		->setForeignKey('ID')
		->setBindingKey('Stat_ID');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>