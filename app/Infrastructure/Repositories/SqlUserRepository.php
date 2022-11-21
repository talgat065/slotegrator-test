<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\UserRepository;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Name;

class SqlUserRepository implements UserRepository
{
    public function getByID(string $uuid): User
    {
        return new User(new UUID($uuid), new Name('Paul'));
        // TODO: Implement getByID() method.
    }
}
