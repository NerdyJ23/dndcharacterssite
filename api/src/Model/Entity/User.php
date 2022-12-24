<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Controller\Security\EncryptionController;

class User extends Entity {
	protected $_hidden = ['ID'];
	protected $_virtual = ['Full_Name', 'encrypted_id'];
	protected $_accessible = [
		'ID' => true, //int
		'Username' => true, //varchar
		'Password' => true, //varchar hashed
		'First_Name' => true, //varchar
		'Last_Name' => true, //varchar nullable
		'Token' => true, //varchar nullable
		// 'token_valid_until' => true, //timestamp nullable
		'Created_At' => true, //timestamp nullable
		'Last_Logged_In' => true //timestamp nullable

	];

	protected function _getEncryptedId() {
		return ((new EncryptionController)->encrypt($this->_fields['ID']));
	}
	protected function _getFullName() {
		$str = $this->first_name;
		if($this->last_name !== null) {
			$str .= ' ' . $this->last_name;
		}
		return $str;
	}
	public function setTokenTimeLimit($days) {
        try {
            intval($days);
        } catch(Exception $e) {
            return date('c', 0);
        }

        $today = date('c');
        return date('c', strtotime($today . ' + ' . $days . 'days'));
    }
}
?>