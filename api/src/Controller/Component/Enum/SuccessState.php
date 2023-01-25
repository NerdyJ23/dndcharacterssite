<?php
namespace App\Controller\Component\Enum;

enum SuccessState {
	case NONE;
	case SUCCESS;
	case PARTIAL;
	case FAIL;

	static function success(SuccessState $old, bool $success):SuccessState {
		if ($success) {
			if ($old == SuccessState::NONE || $old == SuccessState::SUCCESS) {
				return SuccessState::SUCCESS;
			}
			return SuccessState::PARTIAL;
		} else {
			if ($old == SuccessState::NONE || $old == SuccessState::FAIL) {
				return SuccessState::FAIL;
			}
			return SuccessState::PARTIAL;
		}
	}
}