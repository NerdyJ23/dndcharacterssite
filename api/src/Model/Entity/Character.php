<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class Character extends Entity {
	protected $_hidden = ['ID', 'User_Access'];
	protected $_virtual = ['Full_Name', 'id'];
	protected $_accessible = [
		'First_Name' => true,
		'Nickname' => true,
		'Last_Name' => true,
		'Race' => true,
		'Exp' => true,
		'Background' => true,
		'Alignment' => true,
		'Visibility' => true
	];

	protected function _getId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getFull_Name() {
		$str = $this->First_Name;
		if($this->Nickname !== null) {
			$str .= ' "' . $this->Nickname . '"';
		}

		if($this->Last_Name !== null) {
			$str .= ' ' . $this->Last_Name;
		}
		return $str;
	}
}
?>