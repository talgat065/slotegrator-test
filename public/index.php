<?php declare(strict_types=1);

use App\Application\Dto\DrawPrizeRequest;
use App\Application\Handlers\PrizeService;
use App\Application\Repositories\ItemRepository;
use App\Application\Repositories\PrizeRepository;
use App\Application\Repositories\UserRepository;
use App\Infrastructure\Repositories\SqlItemRepository;
use App\Infrastructure\Repositories\SqlPrizeRepository;
use App\Infrastructure\Repositories\SqlUserRepository;

require_once __DIR__ . "./../vendor/autoload.php";

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);

$containerBuilder->addDefinitions([
    ItemRepository::class => \DI\create(SqlItemRepository::class),
    PrizeRepository::class => \DI\create(SqlPrizeRepository::class),
    UserRepository::class => \DI\create(SqlUserRepository::class),
    PrizeService::class => \DI\create(PrizeService::class)->constructor(
        \Di\get(PrizeRepository::class),
        \Di\get(UserRepository::class),
        \Di\get(ItemRepository::class),
    ),
]);

$container = $containerBuilder->build();

$helloWorld = $container->get(PrizeService::class);
$helloWorld->draw(new DrawPrizeRequest('uuid'));
