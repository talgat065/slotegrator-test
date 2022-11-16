<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Prize;

interface PrizeRepository
{
    public function persist(Prize $prize): void;
}
