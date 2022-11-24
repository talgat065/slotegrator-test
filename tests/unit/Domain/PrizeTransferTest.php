<?php declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Prize;
use App\Domain\Shared\UUID;
use App\Domain\User;
use App\Domain\ValueObjects\Bonus;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\PrizeType;
use DomainException;
use PHPUnit\Framework\TestCase;

class PrizeTransferTest extends TestCase
{
    public function testTransferMoneyPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::MONEY);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, true, false);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $prize->transferMoney($user, false);

        $this->assertTrue($prize->isProcessed());
    }

    public function testTransferMoneyPrizeWithConvertation(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::MONEY);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, true, false);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $prize->transferMoney($user, true);

        $this->assertTrue($prize->isProcessed());
        $this->assertEquals(1000, $user->getBonus()->amount());
    }

    public function testCannotTransferMoneyPrizeRepeatedly(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::MONEY);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, true, true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('duplicate money transfering detected');
        $prize->transferMoney($user, true);

        $this->assertFalse($prize->isProcessed());
    }

    public function testCannotTransferUnacceptedMoneyPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::MONEY);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, false, false);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('prize must be accepted before transfering');
        $prize->transferMoney($user, true);

        $this->assertFalse($prize->isProcessed());
    }

    public function testTransferBonusPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::BONUS);
        [$money, $bonus] = [new Money(0), new Bonus(500)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, true, false);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $prize->transferBonusToAccount($user);

        $this->assertTrue($prize->isProcessed());
        $this->assertEquals(500, $user->getBonus()->amount());
    }

    public function testCannotTransferUnacceptedBonusPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::BONUS);
        [$money, $bonus] = [new Money(0), new Bonus(500)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus, null, false, false);

        $this->assertFalse($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('prize must be accepted before transfering');

        $prize->transferBonusToAccount($user);

        $this->assertFalse($prize->isProcessed());
        $this->assertEquals(0, $user->getBonus()->amount());
    }
}
