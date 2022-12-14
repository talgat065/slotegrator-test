<?php declare(strict_types=1);

use App\Application\External\BankService;
use App\Application\External\DeliveryService;
use App\Application\Services\ItemService;
use App\Application\Services\PrizeService;
use App\Application\Repositories\ItemRepository;
use App\Application\Repositories\PrizeRepository;
use App\Application\Repositories\UserRepository;
use App\Application\Services\UserService;
use App\Infrastructure\Repositories\DoctrineItemRepository;
use App\Infrastructure\Repositories\DoctrinePrizeRepository;
use App\Infrastructure\Repositories\DoctrineUserRepository;
use App\Infrastructure\Services\BankImplService;
use App\Infrastructure\Services\DeliveryImplService;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

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
    BankService::class => function (ContainerInterface $c) {
        return new BankImplService();
    },
    DeliveryService::class => function (ContainerInterface $c) {
        return new DeliveryImplService();
    },
    PrizeService::class => function (ContainerInterface $c) {
        return new PrizeService(
            $c->get(PrizeRepository::class),
            $c->get(UserRepository::class),
            $c->get(ItemRepository::class),
            $c->get(BankService::class),
            $c->get(DeliveryService::class)
        );
    },
    UserService::class => function (ContainerInterface $c) {
        return new UserService(
            $c->get(UserRepository::class)
        );
    },
    ItemService::class => function (ContainerInterface $c) {
        return new ItemService(
            $c->get(ItemRepository::class)
        );
    }
];
