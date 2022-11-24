<?php declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\User;

interface UserRepository
{
    /**
     * Finds user by ID otherwise returns null.
     * @param string $id
     * @return User|null
     */
    public function getByID(string $id): ?User;

    /**
     * Saves user data to storage.
     * @param User $user
     * @return void
     */
    public function persist(User $user): void;
}
