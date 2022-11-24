<?php declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Item;

interface ItemRepository
{
    /**
     * Returns all items that hasn't been sent to anyone.
     * @return array
     */
    public function findAll(): array;

    /**
     * Save item to storage
     * @param Item $item
     * @return void
     */
    public function persist(Item $item): void;
}
