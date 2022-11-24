<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Repositories\ItemRepository;
use App\Domain\Item;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Name;

class ItemService
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $userRepository)
    {
        $this->itemRepository = $userRepository;
    }

    public function create(string $name): Item
    {
        $user = new Item(UUID::create(), new Name($name));
        $this->itemRepository->persist($user);

        return $user;
    }
}
