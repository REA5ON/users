<?php

require '../vendor/autoload.php';

use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use DI\ContainerBuilder;
use League\Plates\Engine;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(
    [
        Engine::class => function () {
            return new Engine('../App/views');
        },

        PDO::class => function () {
            $driver = "mysql";
            $host = "localhost";
            $database_name = "app3";
            $username = "root";
            $password = "root";

            return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
        },

        Auth::class => function ($container) {
            return new Auth($container->get('PDO'));
        },

        QueryFactory::class => function() {
            return new QueryFactory('mysql');
        }
    ]
);
$container = $containerBuilder->build();


$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    //homepage
    $r->addRoute('GET', '/', ['App\controllers\UsersController', 'index']);

    $r->addRoute('GET', '/login', ['App\controllers\LoginController', 'index']);
    $r->addRoute('POST', '/login', ['App\controllers\LoginController', 'login']);
    $r->addRoute('GET', '/logout', ['App\controllers\LoginController', 'logOut']);

    $r->addRoute('GET', '/create', ['App\controllers\CreateController', 'index']);
    $r->addRoute('POST', '/create', ['App\controllers\CreateController', 'createUser']);

    $r->addRoute('GET', '/edit/{id:\d+}', ['App\controllers\EditController', 'index']);
    $r->addRoute('POST', '/edit/{id:\d+}', ['App\controllers\EditController', 'editUser']);

    $r->addRoute('GET', '/profile/{id:\d+}', ['App\controllers\ProfileController', 'index']);

    $r->addRoute('GET', '/registration', ['App\controllers\RegisterController', 'index']);
    $r->addRoute('POST', '/registration', ['App\controllers\RegisterController', 'registration']);

    $r->addRoute('GET', '/media/{id:\d+}', ['App\controllers\MediaController', 'index']);
    $r->addRoute('POST', '/media/{id:\d+}', ['App\controllers\MediaController', 'updateImage']);

    $r->addRoute('GET', '/status/{id:\d+}', ['App\controllers\StatusController', 'index']);
    $r->addRoute('POST', '/status/{id:\d+}', ['App\controllers\StatusController', 'setStatus']);

    $r->addRoute('GET', '/security/{id:\d+}', ['App\controllers\SecurityController', 'index']);
    $r->addRoute('POST', '/security/{id:\d+}', ['App\controllers\SecurityController', 'editCredential']);

    $r->addRoute('GET', '/verify_email/[{selector}&{token}]', ['App\controllers\SecurityController', 'emailVerification']);
    $r->addRoute('GET', '/change_email/[{selector}&{token}]', ['App\controllers\SecurityController', 'changeEmail']);


//    $r->addRoute('GET', '/user/{id:\d+}', ['App\controllers\UserController', 'getUser']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {

    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $vars = array($routeInfo[2]);
        $container->call($routeInfo[1], $vars);
        break;
}
