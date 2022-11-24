<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Item;
use App\Domain\Shared\UUID;

interface ItemRepository
{
    /**
     * Returns all items than hasn't been sent to anyone.
     * @return array
     */
    public function findAll(): array;

    /**
     * Returns item by id, otherwise return null.
     * @param string $id
     * @return Item|null
     */
    public function getByID(string $id): ?Item;

    /**
     * Save item to storage
     * @param Item $item
     * @return void
     */
    public function persist(Item $item): void;
}
