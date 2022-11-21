<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\PrizeRepository;
use App\Domain\Prize;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DoctrinePrizeRepository implements PrizeRepository
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @throws Exception
     */
    public function persist(Prize $prize): void
    {
        $item = $prize->getItem();
        $this->db->insert('prizes', [
            'id' => $prize->getId()->value(),
            'user_id' => $prize->getUser()->getID()->value(),
            'item_id' => $item !== null ? $item->getId()->value() : null,
            'type' => $prize->getType()->value(),
            'money' => $prize->getMoney()->amount(),
            'bonus' => $prize->getBonus()->amount(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // TODO: Implement persist() method.
    }
}
