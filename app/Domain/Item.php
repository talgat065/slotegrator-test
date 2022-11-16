<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Name;

final class Item
{
    private UUID $id;
    private Name $name;

    public function __construct(UUID $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return UUID
     */
    public function getId(): UUID
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}
