<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\ItemRepository;
use App\Domain\Item;
use App\Domain\Shared\UUID;
use App\Domain\User;
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
    public function getByID(string $id): ?Item
    {
        $qb = $this->db->createQueryBuilder();

        $data = $qb->select('id', 'name')
            ->from('items')
            ->where('id = ?')
            ->setParameter(0, $id->value())
            ->executeQuery()
            ->fetchAssociative();

        if (!$data) {
            return null;
        }
        return new Item(new UUID($data['id']), new Name($data['name']));
    }
}
