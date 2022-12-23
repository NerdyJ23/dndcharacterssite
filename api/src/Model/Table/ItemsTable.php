<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;

class ItemsTable extends Table {
	public function initialize(array $config): void {

		$this->setDisplayField('Name');
		$this->setTable('Items');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>