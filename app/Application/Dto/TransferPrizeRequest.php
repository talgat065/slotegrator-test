<?php declare(strict_types=1);

namespace App\Application\Dto;

class TransferPrizeRequest
{
    private string $userID;
    private string $prizeID;
    private bool $convertationNeeds;

    public function __construct(string $userID, string $prizeID, bool $convertationNeeds)
    {
        $this->userID = $userID;
        $this->prizeID = $prizeID;
        $this->convertationNeeds = $convertationNeeds;
    }

    public function getPrizeID(): string
    {
        return $this->prizeID;
    }

    public function getUserID(): string
    {
        return $this->userID;
    }

    public function isConvertationNeeds(): bool
    {
        return $this->convertationNeeds;
    }
}
