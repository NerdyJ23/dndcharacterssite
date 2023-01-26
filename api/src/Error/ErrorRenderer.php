<?php
namespace App\Error;

use Cake\Error\ExceptionRenderer;

class ErrorRenderer extends ExceptionRenderer {

    //Custom Errors
    public function logic($e) {
        return $this->sendResponse($e->getCode(), $e->getMessage());
    }

    public function userNotFound($e) {
        return $this->sendResponse($e->getCode(), $e->getMessage());
    }

    public function input($e) {
        return $this->sendResponse($e->getCode(), $e->getMessage());
    }
    private function sendResponse(int $code, string $message) {
        try {
            $res = $this->controller->getResponse();
            $res = $res->withType('application/json');
            $res = $res->withStringBody(json_encode(['errorMessage ' => $message]));
            $res = $res->withStatus($code);
            return $res;
        } catch(Exception $e) {
            return $this->controller->getResponse()->withStringBody("ERROR")->withStatus(500);
        }
    }

    //Default Errors

    public function internalError($e) {
        return $this->sendResponse($e->getCode(), $e->getMessage());
    }
}