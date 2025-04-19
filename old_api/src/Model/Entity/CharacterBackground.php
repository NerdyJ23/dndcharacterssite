<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class CharacterBackground extends Entity {
	protected $_hidden = ['ID', 'Char_ID'];
	protected $_virtual = ['id'];

	protected $_accessible = [
		'ID' => true,
		'Char_ID' => true,
		'Name' => true,
		'Description' => true,
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getCharacterId() {
		return ((new EncryptionController)->encrypt($this->_fields['Char_ID']));
	}
}
?>