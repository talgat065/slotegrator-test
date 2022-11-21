<?php

use App\Application\Handlers\PrizeService;
use App\Application\Repositories\ItemRepository;
use App\Application\Repositories\PrizeRepository;
use App\Application\Repositories\UserRepository;
use App\Infrastructure\Repositories\SqlItemRepository;
use App\Infrastructure\Repositories\SqlPrizeRepository;
use App\Infrastructure\Repositories\SqlUserRepository;
use function DI\create;

return [
    UserRepository::class => create(SqlUserRepository::class),
    ItemRepository::class => create(SqlItemRepository::class),
    PrizeRepository::class => create(SqlPrizeRepository::class),
//    PrizeService::class => create(PrizeService::class),
];
