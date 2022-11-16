<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Domain\Randomizer;
use Exception;

class FakeRandomizer implements Randomizer
{
    private array $returns;

    /**
     * @param int[] $returns
     */
    public function __construct(array $returns)
    {
        $this->returns = $returns;
    }

    /**
     * @throws Exception
     */
    public function getNumber(int $from, int $to): int
    {
        $num = array_shift($this->returns);
        if ($num === null) {
            throw new Exception('No numbers left');
        }
        return $num;
    }
}
