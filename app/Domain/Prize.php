<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\PrizeType;

final class Prize
{
    public const MONEY = 'money';
    public const BONUS = 'bonus';
    public const ITEM = 'item';

    private UUID $id;
    private User $user;
    private Money $money;
    private Bonus $bonus;
    private PrizeType $type;
    private bool $accepted;
    private bool $isSent;
    private ?Item $item;

    public function __construct(
        UUID $id,
        User $user,
        PrizeType $type,
        Money $money,
        Bonus $bonus,
        ?Item $item = null,
        bool $accepted = false,
        bool $isSent = false
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->type = $type;
        $this->money = $money;
        $this->bonus = $bonus;
        $this->accepted = $accepted;
        $this->isSent = $isSent;
        $this->item = $item;
    }

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getType(): PrizeType
    {
        return $this->type;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    public function isSent(): bool
    {
        return $this->isSent;
    }

    /**
     * @return Item|null
     */
    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getBonus(): Bonus
    {
        return $this->bonus;
    }


}
