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

		$this->hasMany('Classes', ['className' => 'CharactersClasses'])
		->setForeignKey('Char_ID')
		->setBindingKey('ID');

		$this->hasMany('Stats', ['className' => 'CharactersStats'])
		->setForeignKey('Char_ID')
		->setBindingKey('ID');

		$this->hasOne('Health', ['className' => 'CharactersHealth'])
		->setForeignKey('Char_ID')
		->setBindingKey('ID');

		$this->hasOne('Background', ['className' => 'CharactersBackgrounds'])
		->setForeignKey('Char_ID')
		->setBindingKey('ID');
	}

	public function beforeSave(EventInterface $event, $entity, $options) {
		return true;
	}
}

?>