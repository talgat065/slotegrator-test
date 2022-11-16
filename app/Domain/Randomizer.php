<?php

declare(strict_types=1);

namespace App\Domain;

interface Randomizer
{
    public function getNumber(int $from, int $to): int;
}
