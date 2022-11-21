<?php declare(strict_types=1);

use App\Application\Handlers\PrizeService;
use App\Application\Repositories\ItemRepository;
use App\Application\Repositories\PrizeRepository;
use App\Application\Repositories\UserRepository;
use App\Infrastructure\Repositories\DoctrineItemRepository;
use App\Infrastructure\Repositories\DoctrinePrizeRepository;
use App\Infrastructure\Repositories\DoctrineUserRepository;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use function DI\create;

return [
    ServerRequestInterface::class => ServerRequestFactory::fromGlobals(),
    'db' => \Doctrine\DBAL\DriverManager::getConnection([
        'dbname' => 'random_prizes',
        'user' => 'root',
        'password' => 'password',
        'host' => 'db',
        'driver' => 'pdo_mysql',
    ]),
    ItemRepository::class => function (ContainerInterface $c) {
        return new DoctrineItemRepository($c->get('db'));
    },
    UserRepository::class => function (ContainerInterface $c) {
        return new DoctrineUserRepository($c->get('db'));
    },
    PrizeRepository::class => function (ContainerInterface $c) {
        return new DoctrinePrizeRepository($c->get('db'));
    },
    PrizeService::class => function (ContainerInterface $c) {
        return new PrizeService(
            $c->get(PrizeRepository::class),
            $c->get(UserRepository::class),
            $c->get(ItemRepository::class),
        );
    },
];
