<?php declare(strict_types=1);

namespace App\Application\Dto;

class AcceptPrizeRequest
{
    private string $userID;
    private string $prizeID;
    private bool $accept;

    public function __construct(string $userID, string $prizeID, bool $accept)
    {
        $this->userID = $userID;
        $this->prizeID = $prizeID;
        $this->accept = $accept;
    }

    public function getUserID(): string
    {
        return $this->userID;
    }

    public function getPrizeID(): string
    {
        return $this->prizeID;
    }

    public function isAccept(): bool
    {
        return $this->accept;
    }
}
