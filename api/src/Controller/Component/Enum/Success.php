<?php
namespace App\Controller\Component\Enum;

enum Success: string {
	case NONE = 'No Change or Process Done';
	case SUCCESS = 'Success';
	case PARTIAL = 'Partial Success';
	case FAIL = 'Complete Failure';

};
?>
