<?php

use App\Ports\Http\Controllers\DrawPrizeController;
use App\Ports\Http\Controllers\PrizesListController;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/draw-prize', DrawPrizeController::class);
    $r->get('/prizes', PrizesListController::class);
});
