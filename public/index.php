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
