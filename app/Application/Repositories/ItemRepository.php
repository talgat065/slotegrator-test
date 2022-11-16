<?php

declare(strict_types=1);

namespace App\Application\Repositories;

interface ItemRepository
{
    public function findAll(): array;
}
