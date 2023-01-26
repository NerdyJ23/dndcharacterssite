<?php
namespace App\Error\Exceptions;
use Cake\Core\Exception\Exception;

class UserNotFoundException extends Exception {
    protected $_messageTemplate = 'Token Mismatch. Clear cookies and try again';
    protected $_defaultCode = 404;
}