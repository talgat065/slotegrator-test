<?php declare(strict_types=1);

use App\Ports\Http\Controllers\PrizeController;
use Laminas\Diactoros\ServerRequestFactory;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Narrowspark\HttpEmitter\SapiEmitter;
use Relay\Relay;

require_once __DIR__ . "./../vendor/autoload.php";

$container = require __DIR__ . '/../config/container.php';
$routes = require __DIR__ . '/../config/routes.php';

$middleware = [
    new FastRoute($routes),
    new RequestHandler($container),
];

$requestHandler = new Relay($middleware);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

(new SapiEmitter())->emit($response);

//$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
//    $r->addRoute('GET', '/', function () {
//        echo  "hellow world";
//    });
//});
//
//// Fetch method and URI from somewhere
//$httpMethod = $_SERVER['REQUEST_METHOD'];
//$uri = $_SERVER['REQUEST_URI'];
//
//// Strip query string (?foo=bar) and decode URI
//if (false !== $pos = strpos($uri, '?')) {
//    $uri = substr($uri, 0, $pos);
//}
//$uri = rawurldecode($uri);
//
//$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
//switch ($routeInfo[0]) {
//    case FastRoute\Dispatcher::NOT_FOUND:
//        echo "not found";
//        // ... 404 Not Found
//        break;
//    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//        $allowedMethods = $routeInfo[1];
//        // ... 405 Method Not Allowed
//        break;
//    case FastRoute\Dispatcher::FOUND:
//        $handler = $routeInfo[1];
//        $vars = $routeInfo[2];
//        $handler(...$vars);
//        // ... call $handler with $vars
//        break;
//}
