<?php

use App\Ports\Http\Controllers\DrawPrizeController;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/draw-prize', DrawPrizeController::class);
});
