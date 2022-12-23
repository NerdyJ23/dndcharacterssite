<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class Character extends Entity {
	protected $_hidden = ['id'];
	protected $_virtual = ['full_name', 'encrypted_id', 'url', 'portrait'];
	protected $_accessible = [
		'id' => true, //int
		'name' => true,
		'race' => true,
		'exp' => true,
		'background' => true,
		'alignment' => true,
		'portrait_url' => true,
	];

	protected function _getEncryptedId() {
		return ((new EncryptionController)->encrypt($this->_fields['id']));
	}
	protected function _getFullName() {
		$str = $this->first_name;
		if($this->last_name !== null) {
			$str .= ' ' . $this->last_name;
		}
		return $str;
	}
	protected function _getPortrait() {
		$str = $this->portrait_url;
		return $str;
	}
	protected function _getUrl() {
		$str = "/characters/" . $this->encrypted_id;
		return $str;
	}
}
?>