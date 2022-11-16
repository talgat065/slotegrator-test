<?php

declare(strict_types=1);

namespace App\Application\Dto;

class DrawPrizeRequest
{
    private string $userID;

    public function __construct(string $userID)
    {
        $this->userID = $userID;
    }

    public function getUserID()
    {
        return $this->userID;
    }
}
