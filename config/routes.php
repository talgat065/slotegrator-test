<?php

use App\Ports\Http\Controllers\DrawPrizeController;
use App\Ports\Http\Controllers\PrizeAcceptController;
use App\Ports\Http\Controllers\PrizesListController;
use App\Ports\Http\Controllers\PrizeTransferController;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/prizes', PrizesListController::class);
    $r->post('/prize/draw', DrawPrizeController::class);
    $r->post('/prize/accept', PrizeAcceptController::class);
    $r->post('/prize/transfer', PrizeTransferController::class);
});
