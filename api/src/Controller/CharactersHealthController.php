<?php
namespace App\Controller;

use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;

class CharactersHealthController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}
}