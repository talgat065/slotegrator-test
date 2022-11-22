<?php declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Application\External\BankService;
use App\Application\External\BankUnavailable;

class BankImplService implements BankService
{
    public function transferMoneyToClient(string $userID, int $amount): void
    {
        // do nothing
    }
}
