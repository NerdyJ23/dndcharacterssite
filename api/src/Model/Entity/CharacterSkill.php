<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class CharacterSkill extends Entity {
	protected $_hidden = ['ID', 'Char_ID', 'Stat_ID'];
	protected $_virtual = ['id'];
	protected $_accessible = [
		'ID' => true, //int
		'Char_ID' => true, //varchar
		'Stat_ID' => true,
		'Proficient' => true,
		'Label' => true,
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getCharacterId() {
		return ((new EncryptionController)->encrypt($this->_fields['Char_ID']));
	}
    protected function _getStatId() {
        return ((new EncryptionController)->encrypt($this->_fields['Stat_ID']));
    }
}
?>