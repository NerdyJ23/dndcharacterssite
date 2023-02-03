<?php
namespace App\Middleware;

use Cake\Http\Cookie\Cookie;
use Cake\I18n\Time;
use Psr\Http\Message\ResponseInterface;
use Cake\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Cake\Http\Exception\ForbiddenException;
use App\Client\Users\UserClient;
use App\Client\Security\AuthClient;
class AuthenticationMiddleware implements MiddlewareInterface {

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		$token = $request->getCookie('token');
		if($token == null || UserClient::getByToken($token) == null || !AuthClient::validToken($token)) { //and auth token correctly
			return $this->_accessDenied();
		}
		return $handler->handle($request);
	}

	private function _accessDenied():Response {
		return $this->_toResponse();
	}
	private function _toResponse(int $code = 403, string $message = 'Login token not found or incorrect'):Response {
		$response = new Response();
		$response = $response->withStatus($code);
		$response = $response->withType('json');
		$response = $response->withStringBody(json_encode(['statusmessage' => $message]));

		return $response;
	}
}

?>