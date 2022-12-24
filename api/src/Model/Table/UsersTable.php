<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;

use App\Controller\ApiController;
use App\Model\Behavior\DeadlineTimestampBehavior;
class UsersTable extends Table {
	public function initialize(array $config): void {

		$this->setTable('Users');
		$this->setDisplayField('First_Name');
		$this->setPrimaryKey('ID');

		$this->addBehavior('Timestamp', [
			'events' => [
				'Model.beforeSave' => [
					'Last_Logged_In' => 'always'
				]
			]
		]);

		// $this->hasMany('Receipts')
		// 	->setForeignKey('User')
		// 	->setBindingKey('id');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		// echo $entity;
		// $entity->id = (1);
		// debug($entity);
		return true;
	}

	public function afterSave(EventInterface $event, $entity, $options) {
		// debug($event);
	}
}

?>