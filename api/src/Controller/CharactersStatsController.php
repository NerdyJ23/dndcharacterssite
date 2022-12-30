<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use Cake\Http\Cookie\Cookie;

class CharactersStatsController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}
}