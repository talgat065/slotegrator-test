<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\PrizeType;

final class SlotMachine
{
    private Randomizer $randomizer;
    private array $items;
    private Money $totalMoney;

    public function __construct(Randomizer $randomizer, Money $money, array $items)
    {
        $this->randomizer = $randomizer;
        $this->items = $items;
        $this->totalMoney = $money;
    }

    public function getPrize(User $user): Prize
    {
        $prizeTypes = [Prize::MONEY, Prize::BONUS, Prize::ITEM];
        [$min, $max] = [0, sizeof($prizeTypes) - 1];

        $randomIndex = $this->randomizer->getNumber($min, $max);
        $type = new PrizeType($prizeTypes[$randomIndex]);

        $amount = 0;
        $money = new Money(0);
        $bonus = new Bonus(0);
        $item = null;
        switch ($type->value()) {
            case Prize::MONEY:
                $amount = $this->getRandomSum($type);
                $money = new Money($amount);
                break;
            case Prize::BONUS:
                $amount = $this->getRandomSum($type);
                $bonus = new Bonus($amount);
                break;
            case Prize::ITEM:
                $item = $this->getRandomItem();
        }

        return new Prize(
            UUID::create(),
            $user,
            $type,
            $money,
            $bonus,
            $item
        );
    }

    private function getRandomSum(): int
    {
        return $this->randomizer->getNumber(0, 100);
    }

    private function getRandomItem(): Item
    {
        return $this->items[$this->randomizer->getNumber(0, sizeof($this->items) - 1)];
    }
}
