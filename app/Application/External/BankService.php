<?php declare(strict_types=1);

namespace App\Application\External;

interface BankService
{
    /**
     * @throws BankUnavailable
     * @return void
     */
    public function transferMoneyToClient(string $userID, int $amount): void;
}
