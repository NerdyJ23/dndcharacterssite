<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

use App\Middleware\AuthenticationMiddleware;

return static function (RouteBuilder $routes) {

    $routes->setRouteClass(DashedRoute::class);
	$routes->registerMiddleware('auth', new AuthenticationMiddleware());
    $routes->scope('/', function (RouteBuilder $builder) {

        $builder->connect('/', 'Api::index');
		$builder->connect('/login', 'Users::login');

		$builder->scope('/user', function (RouteBuilder $builder) {
			$builder->applyMiddleware('auth');
			$builder->post('/', 'Users::create');
			$builder->get('/', 'Users::get');

			// $builder->patch('/{id}', 'Users::edit')->setPass(['id']);
			// $builder->get('/{id}', 'Users::get')->setPass(['id']);
		});

		$builder->scope('/characters', function (RouteBuilder $builder) {
			$builder->connect('/list', 'Characters::listPublicCharacters');
			$builder->applyMiddleware('auth');
			$builder->connect('/', 'Characters::list');
		});
		$builder->resources('Characters', function (RouteBuilder $builder) {
			$builder->connect('/', 'Characters::get');

			$builder->scope('/image', function (RouteBuilder $builder) {
				$builder->get('/', 'Characters::getCharacterImage');
				$builder->post('/', 'Characters::uploadCharacterImage');
			});

			$builder->scope('/classes', function (RouteBuilder $builder) {
				$builder->get('/', 'CharactersClasses::list');
				$builder->post('/', 'CharactersClasses::create');
			});

			$builder->resources('Classes', function (RouteBuilder $builder) {
				$builder->patch('/', 'CharactersClasses::update');
				$builder->delete('/', 'CharactersClasses::delete');
			});

			$builder->scope('/stats', function (RouteBuilder $builder) {
				$builder->get('/', 'CharactersStats::list');
				$builder->post('/', 'CharactersStats::create');
			});

			$builder->resources('Stats', function (RouteBuilder $builder) {
				$builder->patch('/', 'CharactersStats::update');
				$builder->delete('/', 'CharactersStats::delete');
			});

			$builder->scope('/background', function (RouteBuilder $builder) {
				$builder->get('/', 'CharactersBackground::list');
			});

			// $builder->resources('Background', function (RouteBuilder $builder) {
			// 	$builder->patch('/', 'CharactersStats::update');
			// });

			$builder->connect('/', 'Characters::get')->setPass(['id']);
		});
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder) {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
