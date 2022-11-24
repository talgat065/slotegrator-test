<?php declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\ItemRepository;
use App\Domain\Item;
use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Name;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DoctrineItemRepository implements ItemRepository
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $qb = $this->db->createQueryBuilder();
        $data = $qb->select('id', 'name')->from('items')->where('given = 0')->executeQuery()->fetchAllAssociative();
        $result = [];
        foreach ($data as $item) {
            $result[] = new Item(new UUID($item['id']), new Name($item['name']));
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function persist(Item $item): void
    {
        $qb = $this->db->createQueryBuilder();

        $exists = (bool)$qb->select('true')
            ->from('items', 'u')
            ->where('u.id = ' . $qb->createNamedParameter($item->getId()->value()))
            ->executeQuery()
            ->rowCount();

        if (!$exists) {
            $this->db->insert('items', [
                'id' => $item->getID()->value(),
                'name' => $item->getName()->value(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->db->update('items', [
                'name' => $item->getName()->value(),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'id' => $item->getId()->value(),
            ]);
        }
    }
}
