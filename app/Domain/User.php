<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Name;

class User
{
    private UUID $id;
    private Name $name;
    private Bonus $bonus;

    public function __construct(UUID $id, Name $name, ?Bonus $bonus)
    {
        $this->id = $id;
        $this->name = $name;
        if ($bonus === null) {
            $this->bonus = new Bonus(0);
        } else {
            $this->bonus = $bonus;
        }
    }

    public function getID(): UUID
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getBonus(): Bonus
    {
        return $this->bonus;
    }

    public function addBonus(Bonus $bonus): void
    {
        $amount = $this->bonus->amount() + $bonus->amount();
        $this->bonus = new Bonus($amount);
    }
}
