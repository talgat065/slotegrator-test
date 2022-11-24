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

class PrizeAcceptTest extends TestCase
{
    public function testAcceptMoneyPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::MONEY);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus);

        $this->assertFalse($prize->isAccepted());

        $prize->accept($user);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());
    }

    public function testAcceptBonusPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::BONUS);
        [$money, $bonus] = [new Money(0), new Bonus(500)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus);

        $this->assertFalse($prize->isAccepted());

        $prize->accept($user);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());
    }

    public function testAcceptItemPrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::ITEM);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus);

        $this->assertFalse($prize->isAccepted());

        $prize->accept($user);

        $this->assertTrue($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());
    }

    public function testDeclinePrize(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::ITEM);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus);

        $this->assertFalse($prize->isAccepted());

        $prize->decline($user);

        $this->assertFalse($prize->isAccepted());
        $this->assertFalse($prize->isProcessed());
    }

    public function testCannotAcceptPrizeIfUserIsNotOwner(): void
    {
        $userID = UUID::create();
        $prizeID = UUID::create();
        $user = new User($userID, new Name('Paul'));
        $type = new PrizeType(Prize::ITEM);
        [$money, $bonus] = [new Money(500), new Bonus(0)];

        $prize = new Prize($prizeID, $user, $type, $money, $bonus);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('user must be owner of the prize to process operation');

        $prize->accept(new User(UUID::create(), new Name('Lisa')));

        $this->assertFalse($prize->isAccepted());
    }
}
