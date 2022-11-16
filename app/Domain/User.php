<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Name;

class User
{
    private UUID $id;
    private Name $name;

    public function __construct(UUID $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getID(): UUID
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
