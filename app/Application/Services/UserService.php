<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Repositories\UserRepository;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Name;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(string $name): User
    {
        $user = new User(UUID::create(), new Name($name));
        $this->userRepository->persist($user);

        return $user;
    }
}
