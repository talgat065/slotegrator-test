<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\UserRepository;
use App\Domain\Item;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Name;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DoctrineUserRepository implements UserRepository
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @throws Exception
     */
    public function getByID(string $id): ?User
    {
        $qb = $this->db->createQueryBuilder();

        $data = $qb->select('id', 'name', 'bonus')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->executeQuery()
            ->fetchAssociative();

        if (!$data) {
            return null;
        }
        return new User(new UUID($data['id']), new Name($data['name']), new Bonus((int)$data['bonus']));
    }

    /**
     * @throws Exception
     */
    public function updateBonus(User $user): void
    {
        $this->db->update('users', [
            'bonus' => $user->getBonus()->amount(),
            'updated_at' => date('Y-m-d H:i:s'),
        ], [
            'id' => $user->getId()->value(),
        ]);
    }
}
