<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\ItemRepository;
use App\Domain\Item;
use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Name;

class SqlItemRepository implements ItemRepository
{
    public function findAll(): array
    {
        return [
            new Item(UUID::create(), new Name('IPhone 5S')),
        ];
        // TODO: Implement findAll() method.
    }
}
