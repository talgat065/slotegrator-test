<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\UserRepository;
use App\Domain\Item;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Name;
use Doctrine\DBAL\Connection;

class DoctrineUserRepository implements UserRepository
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    public function getByID(string $uuid): ?User
    {
        $qb = $this->db->createQueryBuilder();
        $data = $qb->select('id', 'name')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $uuid)
            ->executeQuery()
            ->fetchAssociative();
        if (!$data) {
            return null;
        }
        return new User(new UUID($data['id']), new Name($data['name']));
    }
}
