<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Shared\UUID;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\PrizeType;

final class SlotMachine
{
    private const MIN_BONUS_WIN = 100;
    private const MAX_BONUS_WIN = 5000;

    private const MIN_MONEY_WIN = 10;
    private const MAX_MONEY_WIN = 500;

    private Randomizer $randomizer;
    private array $items;
    private Money $money;

    public function __construct(Randomizer $randomizer, Money $money, array $items)
    {
        $this->randomizer = $randomizer;
        $this->items = $items;
        $this->money = $money;
    }

    public function getPrize(User $user): Prize
    {
        $type = $this->getRandomPrizeType();

        $money = $this->getMoney($type);
        $bonus = $this->getBonus($type);
        $item = $this->getRandomItem();

        return new Prize(UUID::create(), $user, $type, $money, $bonus, $item);
    }

    private function getRandomPrizeType(): PrizeType
    {
        $prizeTypes = [Prize::BONUS];

        if ($this->money->amount() >= self::MIN_MONEY_WIN) {
            $prizeTypes[] = Prize::MONEY;
        }
        if (sizeof($this->items) > 0) {
            $prizeTypes[] = Prize::ITEM;
        }

        $typesSize = sizeof($prizeTypes);
        $randomIndex = $this->randomizer->getNumber(0, --$typesSize);

        try {
            return new PrizeType($prizeTypes[$randomIndex]);
        } catch (\Throwable $e){
            print_r($prizeTypes);
            print_r($typesSize.PHP_EOL);
            print_r($randomIndex.PHP_EOL);
        }

    }

    public function getMoney(PrizeType $type): Money
    {
        if ($type->value() === Prize::MONEY) {
            $maxWin = min($this->money->amount(), self::MAX_MONEY_WIN);
            $amount = $this->randomizer->getNumber(self::MIN_MONEY_WIN, $maxWin);
            return new Money($amount);
        }

        return new Money(0);
    }

    public function getBonus(PrizeType $type): Bonus
    {
        if ($type->value() === Prize::BONUS) {
            $amount = $this->randomizer->getNumber(self::MIN_BONUS_WIN, self::MAX_BONUS_WIN);
            return new Bonus($amount);
        }

        return new Bonus(0);
    }

    private function getRandomItem(): ?Item
    {
        return $this->items[$this->randomizer->getNumber(0, sizeof($this->items) - 1)] ?? null;
    }
}
