<?php

use App\Ports\Http\Controllers\DrawPrizeController;
use App\Ports\Http\Controllers\PrizeAcceptController;
use App\Ports\Http\Controllers\PrizeDeliveryController;
use App\Ports\Http\Controllers\PrizesListController;
use App\Ports\Http\Controllers\PrizeTransferController;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->addGroup('/api/v1', function (RouteCollector $r) {
        $r->get('/prizes', PrizesListController::class);

        $r->addGroup('/prize', function (RouteCollector $r) {
            $r->post('/draw', DrawPrizeController::class);
            $r->post('/accept', PrizeAcceptController::class);
            $r->post('/transfer', PrizeTransferController::class);
            $r->post('/delivery', PrizeDeliveryController::class);
        });
    });
});
