<?php
namespace App\Error;

use Cake\Error\ExceptionRenderer;
use Cake\View\JsonView;

class ErrorRenderer extends ExceptionRenderer {

    public function initialize(): void {
        $this->viewBuilder()->setClassName('Json');
    }

    public function logic($e) {
        $res = $this->controller->getResponse();
        $res = $res->withType('application/json');
        $res = $res->withStringBody(json_encode(['errorMessage ' => $e->getMessage()]));
        $res = $res->withStatus($e->getCode());
        return $res;
    }
}