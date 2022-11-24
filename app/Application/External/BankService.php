<?php declare(strict_types=1);

namespace App\Application\External;

interface BankService
{
    /**
     * Sends money to a user's bank account
     * @param string $userID
     * @param int $amount
     * @return void
     * @throws BankUnavailable
     */
    public function transferMoneyToClient(string $userID, int $amount): void;
}
