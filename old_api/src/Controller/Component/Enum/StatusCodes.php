<?php
namespace App\Controller\Component\Enum;

enum StatusCodes {
	//200
	case SUCCESS; //200
	case CREATED; //201
	case NO_CONTENT; //204

	case USER_ERROR; //400
	case TOKEN_MISMATCH; //403
	case ACCESS_DENIED; //403
	case NOT_FOUND; //404

	case SERVER_ERROR; //500
};
?>