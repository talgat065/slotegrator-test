<?php declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Item;
use App\Domain\Prize;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\PrizeType;
use DomainException;
use PHPUnit\Framework\TestCase;

class PrizeDeliveryTest extends TestCase
{
    public function testSendOnDeliveryItemPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::ITEM);
        [$money, $bonus] = [new Money(0), new Bonus(0)];
        $item = new Item(UUID::create(), new Name('IPhone 5S Grey'));

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, $item, true, false);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $prize->sendOnDelivery($user, false);

        $this->assertTrue($prize->isProcessed());
    }

    public function testCannotSendOnDeliveryUnacceptedItemPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::ITEM);
        [$money, $bonus] = [new Money(0), new Bonus(0)];
        $item = new Item(UUID::create(), new Name('IPhone 5S Grey'));

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, $item, false, false);

        $this->assertFalse($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('prize must be accepted before sending on delivery');

        $prize->sendOnDelivery($user, false);

        $this->assertFalse($prize->isProcessed());
    }

    public function testCannotSendOnDeliveryItemPrizeRepeatedly(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::ITEM);
        [$money, $bonus] = [new Money(0), new Bonus(0)];
        $item = new Item(UUID::create(), new Name('IPhone 5S Grey'));

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, $item, true, true);

        $this->assertTrue($prize->isAccepted());
        $this->assertTrue($prize->isProcessed());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('item has already been sent on delivery');

        $prize->sendOnDelivery($user, false);

        $this->assertTrue($prize->isProcessed());
    }

    public function testCannotSendOnDeliveryBonusPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::BONUS);
        [$money, $bonus] = [new Money(0), new Bonus(500)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, true, false);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('can not send on delivery this kind of prize');

        $prize->sendOnDelivery($user, false);

        $this->assertFalse($prize->isProcessed());
    }

    public function testCannotSendOnDeliveryMoneyPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::MONEY);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, true, false);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('can not send on delivery this kind of prize');

        $prize->sendOnDelivery($user, false);

        $this->assertFalse($prize->isProcessed());
    }
}
