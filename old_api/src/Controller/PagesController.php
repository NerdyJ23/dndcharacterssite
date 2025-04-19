<?php
declare(strict_types=1);
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;


class PagesController extends Controller
{
	public function initialize(): void {
		parent::initialize();
		$this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
	}

	public function notfound() {
		$this->set('status','403');
		$this->set('statusmessage','Access denied');

		$this->viewBuilder()->setOption('serialize', true);
	}
}


?>