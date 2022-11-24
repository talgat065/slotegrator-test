<?php declare(strict_types=1);

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\UnknownPrizeType;
use App\Domain\Prize;

final class PrizeType
{
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, [Prize::MONEY, Prize::BONUS, Prize::ITEM])) {
            throw new UnknownPrizeType('Provided prize type is unkown');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
