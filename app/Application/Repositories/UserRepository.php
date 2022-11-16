<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\User;

interface UserRepository
{
    public function getByID(string $uuid): User;
}
