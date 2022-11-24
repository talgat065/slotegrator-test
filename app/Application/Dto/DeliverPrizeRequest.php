<?php declare(strict_types=1);

namespace App\Application\Dto;

class DeliverPrizeRequest
{
    private string $userID;
    private string $prizeID;

    public function __construct(string $userID, string $prizeID)
    {
        $this->userID = $userID;
        $this->prizeID = $prizeID;
    }

    public function getPrizeID(): string
    {
        return $this->prizeID;
    }

    public function getUserID(): string
    {
        return $this->userID;
    }
}
