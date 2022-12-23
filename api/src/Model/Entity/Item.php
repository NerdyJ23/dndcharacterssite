<?php

namespace App\Model\Entity;
use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class Item extends Entity {
	protected $_hidden = ['Owner', 'ID', 'Receipt'];
	protected $_accessible = [
		'ID' => true,
		'Receipt' => true,
		'Name' => true,
		'Count' => true,
		'Cost' => true,
		'Category' => true
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}

	// protected function _getReceipt() {
	// 	return ((new EncryptionController)->encrypt($this->_fields['Receipt']));
	// }
}
?>