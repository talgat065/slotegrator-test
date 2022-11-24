<?php declare(strict_types=1);

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
        $qb = $this->db->createQueryBuilder();
        $exists = (bool)$qb->select('true')
            ->from('prizes', 'p')
            ->where('p.id = ' . $qb->createNamedParameter($prize->getId()->value()))
            ->executeQuery()
            ->rowCount();
        if (!$exists) {
            $this->db->insert('prizes', [
                'id' => $prize->getId()->value(),
                'user_id' => $prize->getUser()->getID()->value(),
                'item_id' => $item !== null ? $item->getId()->value() : null,
                'type' => $prize->getType()->value(),
                'money' => $prize->getMoney()->amount(),
                'bonus' => $prize->getBonus()->amount(),
                'accepted' => $prize->isAccepted() ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->db->update('prizes', [
                'accepted' => $prize->isAccepted() ? 1 : 0,
                'processed' => $prize->isProcessed() ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'id' => $prize->getId()->value(),
            ]);
        }
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
            ->andWhere('p.accepted = true')
            ->orderBy('p.created_at', 'desc')
            ->executeQuery()
            ->fetchAllAssociative();

        $result = [];
        foreach ($data as $item) {
            $result[] = new Prize(
                new UUID($item['prize_id']),
                new User(new UUID($item['user_id']), new Name($item['user_name']), new Bonus((int)$item['bonus'])),
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

    /**
     * @throws Exception
     */
    public function getByID(string $id): ?Prize
    {
        $qb = $this->db->createQueryBuilder();

        $data = $qb->select(
            'p.id as prize_id',
            'u.id as user_id',
            'u.name as user_name',
            'u.bonus as user_bonus',
            'i.id as item_id',
            'i.name as item_name',
            'p.type',
            'p.money',
            'p.bonus',
            'p.accepted',
            'p.processed',
            'p.created_at',
        )
            ->from('prizes', 'p')
            ->innerJoin('p', 'users', 'u', 'p.user_id = u.id')
            ->leftJoin('p', 'items', 'i', 'p.item_id = i.id')
            ->where('p.id = ?')
            ->setParameter(0, $id)
            ->executeQuery()
            ->fetchAssociative();

        if (!$data) {
            return null;
        }
        return new Prize(
            new UUID($data['prize_id']),
            new User(new UUID($data['user_id']), new Name($data['user_name']), new Bonus((int)$data['bonus'])),
            new PrizeType($data['type']),
            new Money((int)$data['money']),
            new Bonus((int)$data['bonus']),
            $data['item_id'] != null ? new Item(new UUID($data['item_id']), new Name($data['item_name'])) : null,
            $data['accepted'] == true,
            $data['processed'] == true,
        );
    }

    /**
     * @throws Exception
     */
    public function findUnprocessedMoneyPrizes(int $batchCount): array
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
            'p.accepted',
            'p.processed',
            'p.created_at',
        )
            ->from('prizes', 'p')
            ->innerJoin('p', 'users', 'u', 'p.user_id = u.id')
            ->leftJoin('p', 'items', 'i', 'p.item_id = i.id')
            ->where('p.accepted = true')
            ->andWhere('p.processed = 0')
            ->andWhere('p.type = ?')
            ->setParameter(0, Prize::MONEY)
            ->orderBy('p.created_at', 'desc')
            ->setFirstResult(0)
            ->setMaxResults($batchCount)
            ->executeQuery()
            ->fetchAllAssociative();

        $result = [];
        foreach ($data as $item) {
            $result[] = new Prize(
                new UUID($item['prize_id']),
                new User(new UUID($item['user_id']), new Name($item['user_name']), new Bonus((int)$item['bonus'])),
                new PrizeType($item['type']),
                new Money((int)$item['money']),
                new Bonus((int)$item['bonus']),
                $item['item_id'] != null ? new Item(new UUID($item['item_id']), new Name($item['item_name'])) : null,
                $item['accepted'] == 1,
                $item['processed'] == 1,
            );
        }
        return $result;
    }
}
