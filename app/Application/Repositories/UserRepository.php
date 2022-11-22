<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\User;

interface UserRepository
{
    public function getByID(string $id): ?User;

    public function updateBonus(User $user): void;
}
