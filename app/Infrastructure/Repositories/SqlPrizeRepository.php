<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\PrizeRepository;
use App\Domain\Prize;

class SqlPrizeRepository implements PrizeRepository
{
    public function persist(Prize $prize): void
    {
        // TODO: Implement persist() method.
    }
}
