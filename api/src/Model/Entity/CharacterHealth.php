<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class CharacterHealth extends Entity {
	protected $_hidden = ['ID', 'Char_ID'];
	// protected $_virtual = ['characterId', 'id'];
	protected $_accessible = [
		'ID' => true, //int
		'Char_ID' => true, //varchar
		'Current_Health' => true,
		'Max_Health' => true,
		'Temporary_Health' => true,
		'Hit_Die' => true,
		'Death_Fails' => true,
		'Death_Success' => true
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getCharacterId() {
		return ((new EncryptionController)->encrypt($this->_fields['Char_ID']));
	}
}
?>