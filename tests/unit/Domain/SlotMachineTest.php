<?php declare(strict_types=1);

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
        $randomizer = new FakeRandomizer([1, 20, 0]);
        $slotMachine = new SlotMachine($randomizer, $money, []);

        $user = new User(UUID::create(), new Name('Paul'));
        $prize = $slotMachine->drawPrize($user);

        $this->assertEquals(Prize::MONEY, $prize->getType()->value());
        $this->assertEquals(20, $prize->getMoney()->amount());
        $this->assertEquals(0, $prize->getBonus()->amount());
        $this->assertNull($prize->getItem());
        $this->assertEquals($prize->getUser(), $user);
    }

    public function testBonusPrize(): void
    {
        $money = new Money(100);
        $randomizer = new FakeRandomizer([0, 500, 0]);
        $slotMachine = new SlotMachine($randomizer, $money, []);

        $user = new User(UUID::create(), new Name('Robert Paulson'));
        $prize = $slotMachine->drawPrize($user);

        $this->assertEquals(Prize::BONUS, $prize->getType()->value());
        $this->assertEquals(0, $prize->getMoney()->amount());
        $this->assertEquals(500, $prize->getBonus()->amount());
        $this->assertNull($prize->getItem());
    }

    public function testItemPrize(): void
    {
        $money = new Money(0);
        $randomizer = new FakeRandomizer([1, 1]);
        [$item1, $item2, $item3] = [
            new Item(UUID::create(), new Name('LA HABANA Scented Candle 200 ml')),
            new Item(UUID::create(), new Name('Carne Bollente Men On Fire Towel')),
            new Item(UUID::create(), new Name('La Soufflerie Boule Vase in Green')),
        ];

        $slotMachine = new SlotMachine($randomizer, $money, [$item1, $item2, $item3]);

        $user = new User(UUID::create(), new Name('Robert Paulson'));
        $prize = $slotMachine->drawPrize($user);

        $this->assertEquals(Prize::ITEM, $prize->getType()->value());
        $this->assertEquals(0, $prize->getMoney()->amount());
        $this->assertEquals(0, $prize->getBonus()->amount());
        $this->assertNotNull($prize->getItem());
        $this->assertSame($item2, $prize->getItem());
    }
}
