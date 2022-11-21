<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\PrizeRepository;
use App\Domain\Prize;
use Doctrine\DBAL\Connection;

class SqlPrizeRepository implements PrizeRepository
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    public function persist(Prize $prize): void
    {
        // TODO: Implement persist() method.
    }
}
