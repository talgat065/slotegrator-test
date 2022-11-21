<?php declare(strict_types=1);

use App\Application\Handlers\PrizeService;
use App\Application\Repositories\ItemRepository;
use App\Application\Repositories\PrizeRepository;
use App\Application\Repositories\UserRepository;
use App\Infrastructure\Repositories\SqlItemRepository;
use App\Infrastructure\Repositories\SqlPrizeRepository;
use App\Infrastructure\Repositories\SqlUserRepository;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use function DI\create;

return [
    ServerRequestInterface::class => ServerRequestFactory::fromGlobals(),
    UserRepository::class => create(SqlUserRepository::class),
    ItemRepository::class => create(SqlItemRepository::class),
    PrizeRepository::class => create(SqlPrizeRepository::class),
    PrizeService::class => function (ContainerInterface $c) {
        return new PrizeService(
            $c->get(PrizeRepository::class),
            $c->get(UserRepository::class),
            $c->get(ItemRepository::class),
        );
    },
];
