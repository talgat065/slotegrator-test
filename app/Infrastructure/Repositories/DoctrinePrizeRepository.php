<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\PrizeRepository;
use App\Domain\Item;
use App\Domain\Prize;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\PrizeType;
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
    }

    /**
     * @return Prize[]
     * @throws Exception
     */
    public function prizesList(UUID $userID): array
    {
        $qb = $this->db->createQueryBuilder();

        $data = $qb->select(
                'p.id as prize_id',
                'u.id as user_id',
                'u.name as user_name',
                'i.id as item_id',
                'i.name as item_name',
                'p.type',
                'p.money',
                'p.bonus',
                'p.processed',
                'p.created_at',
        )
            ->from('prizes', 'p')
            ->innerJoin('p', 'users', 'u', 'p.user_id = u.id')
            ->leftJoin('p', 'items', 'i', 'p.item_id = i.id')
            ->where('p.user_id = ' . $qb->createNamedParameter($userID->value()))
            ->orderBy('p.created_at', 'desc')
            ->executeQuery()
            ->fetchAllAssociative();

        $result = [];
        foreach ($data as $item) {
            $result[] = new Prize(
                new UUID($item['prize_id']),
                new User(new UUID($item['user_id']), new Name($item['user_name'])),
                new PrizeType($item['type']),
                new Money((int)$item['money']),
                new Bonus((int)$item['bonus']),
                $item['item_id'] != null ? new Item(new UUID($item['item_id']), new Name($item['item_name'])) : null,
                false,
                $item['p.processed'] == true,
            );
        }
        return $result;
    }
}
