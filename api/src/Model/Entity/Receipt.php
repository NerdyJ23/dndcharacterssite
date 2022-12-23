<?php

namespace App\Model\Entity;
use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class Receipt extends Entity {
	protected $_virtual = ['encrypted_id'];
	protected $_hidden = ['encrypted_id', 'CreatedUTC'];

	protected $_accessible = [
		'ID' => false,
		'User' => true,
		'Name' => true,
		'Location' => true,
		'ReceiptNumber' => true,
		'Cost' => true,
		'Date' => true,
		'Category' => true,
		'Archive' => true,
		'EditedUTC' => true,
	];

	protected function _getEncodedId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getUserId() {
		return ((new EncryptionController)->encrypt($this->_fields['User']));
	}
	protected function _getCategory() {
		return $this->_fields['Category'] == null ? 'None' : $this->_fields['Category'];
	}

	public function setName($name) {
		$this->_fields['Location'] = $name;
		$this->setDirty('Location');
	}

	public function setDate($date) {
		$this->_fields['Date'] = $date;
		$this->setDirty('Date');
	}

	public function validate(): bool {
		return $this->has('Location');
	}
}
?>