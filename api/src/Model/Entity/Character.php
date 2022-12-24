<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class Character extends Entity {
	protected $_hidden = ['ID', 'User_Access', 'portrait_url'];
	protected $_virtual = ['Full_Name', 'encrypted_id', 'Url', 'Portrait'];
	protected $_accessible = [
		'First_Name' => true,
		'Last_Name' => true,
		'Race' => true,
		'Exp' => true,
		'Background' => true,
		'Alignment' => true,
		'portrait_url' => true,
	];

	protected function _getEncryptedId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getFull_Name() {
		$str = $this->First_Name;
		if($this->Last_Name !== null) {
			$str .= ' ' . $this->Last_Name;
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