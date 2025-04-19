<?php
namespace App\Error\Exceptions;
use Cake\Core\Exception\Exception;

class InputException extends Exception {
    protected $_messageTemplate = '%s';
    protected $_defaultCode = 400;
}