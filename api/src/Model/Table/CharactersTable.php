<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class CharactersTable extends Table {
	public function initialize(array $config): void {
		$this->setTable('Characters');

		// $this->hasMany('Items')
		// 	->setForeignKey('Receipt')
		// 	->setBindingKey('ID');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>