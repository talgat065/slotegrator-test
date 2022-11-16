<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Item;
use App\Domain\Prize;
use App\Domain\PrizeType;
use App\Domain\Shared\UUID;
use App\Domain\SlotMachine;
use App\Domain\User;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Name;
use Tests\Helpers\FakeRandomizer;
use PHPUnit\Framework\TestCase;

final class SlotMachineTest extends TestCase
{
    public function testMoneyPrize(): void
    {
        $money = new Money(100);
        $randomizer = new FakeRandomizer([0, 20]);
        $slotMachine = new SlotMachine($randomizer, $money, [
            new Item(UUID::create(), new Name('LA HABANA Scented Candle 200 ml')),
            new Item(UUID::create(), new Name('Carne Bollente Men On Fire Towel')),
            new Item(UUID::create(), new Name('La Soufflerie Boule Vase in Green')),
        ]);

        $user = new User(UUID::create(), new Name('Paul'));
        $prize = $slotMachine->getPrize($user);

        $this->assertEquals(Prize::MONEY, $prize->getType()->value());
        $this->assertEquals(20, $prize->getMoney()->amount());
        $this->assertEquals($prize->getUser(), $user);
    }

    public function testBonusPrize(): void
    {
        $money = new Money(100);
        $randomizer = new FakeRandomizer([1, 500]);
        $slotMachine = new SlotMachine($randomizer, $money, [
            new Item(UUID::create(), new Name('LA HABANA Scented Candle 200 ml')),
            new Item(UUID::create(), new Name('Carne Bollente Men On Fire Towel')),
            new Item(UUID::create(), new Name('La Soufflerie Boule Vase in Green')),
        ]);

        $user = new User(UUID::create(), new Name('Robert Paulson'));
        $prize = $slotMachine->getPrize($user);

        $this->assertEquals(Prize::BONUS, $prize->getType()->value());
        $this->assertEquals(500, $prize->getMoney()->amount());
    }
}
