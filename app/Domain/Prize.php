<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\PrizeType;
use DomainException;

final class Prize
{
    private const MONEY_BONUS_COEF = 2;

    public const MONEY = 'money';
    public const BONUS = 'bonus';
    public const ITEM = 'item';

    private UUID $id;
    private User $user;
    private Money $money;
    private Bonus $bonus;
    private PrizeType $type;
    private bool $accepted;
    private bool $processed;
    private ?Item $item;

    public function __construct(
        UUID $id,
        User $user,
        PrizeType $type,
        Money $money,
        Bonus $bonus,
        ?Item $item = null,
        bool $accepted = false,
        bool $processed = false
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->type = $type;
        $this->money = $money;
        $this->bonus = $bonus;
        $this->accepted = $accepted;
        $this->processed = $processed;
        $this->item = $item;
    }

    public function accept(User $user): void
    {
        $this->checkOwnership($user);

        $this->accepted = true;
    }

    public function decline(User $user): void
    {
        $this->checkOwnership($user);

        $this->accepted = false;
    }

    public function transferBonusToAccount(User $user): void
    {
        $this->checkOwnership($user);

        if (!$this->isAccepted()) {
            throw new DomainException('prize must accepted before transfering');
        }

        if ($this->isProcessed()) {
            return;
        }

        $user->addBonus($this->bonus);
        $this->processed = true;
    }

    public function transferMoney(User $user, bool $needsConvertation): void
    {
        $this->checkOwnership($user);

        if (!$this->isAccepted()) {
            throw new DomainException('prize must accepted before transfering');
        }

        if ($this->isProcessed()) {
            throw new DomainException('duplicate money transfering detected');
        }

        if ($needsConvertation) {
            $user->addBonus(new Bonus($this->money->amount() * self::MONEY_BONUS_COEF));
        }

        $this->processed = true;
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

    public function isProcessed(): bool
    {
        return $this->processed;
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

    /**
     * @param User $user
     * @return void
     */
    private function checkOwnership(User $user): void
    {
        if (!$this->user->getID()->equals($user->getID())) {
            throw new DomainException('user must be owner of the prize to process operation');
        }
    }


}
