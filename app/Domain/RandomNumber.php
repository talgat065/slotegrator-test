<?php

declare(strict_types=1);

namespace App\Domain;

class RandomNumber implements Randomizer
{
    public function getNumber(int $from, int $to): int
    {
        return rand($from, $to);
    }
}
