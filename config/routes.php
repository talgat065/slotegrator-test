<?php

use App\Ports\Http\Controllers\PrizeController;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/prize', PrizeController::class);
});
