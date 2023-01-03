<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class CharacterClass extends Entity {
	protected $_hidden = ['ID', 'Char_ID'];
	protected $_virtual = ['id'];
	protected $_accessible = [
		'ID' => true, //int
		'Char_ID' => true, //varchar
		'Class' => true, //varchar hashed
		'Level' => true, //varchar
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getCharacterId() {
		return ((new EncryptionController)->encrypt($this->_fields['Char_ID']));
	}
}
?>