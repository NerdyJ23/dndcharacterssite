<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class ReceiptsTable extends Table {
	public function initialize(array $config): void {
		// Configure::load('app_local');
		// $datasource = Configure::read('Datasources.default');

		// $url = 'mysql:://' . $datasource['username'] . ':' . $datasource['password'] . '@' . $datasource['host'] . '/' . $datasource['database'];
		// ConnectionManager::setConfig('default', ['url' => $url]);

		$this->setDisplayField('Location');
		$this->setTable('Receipts');

		$this->hasMany('Items')
			->setForeignKey('Receipt')
			->setBindingKey('ID');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>