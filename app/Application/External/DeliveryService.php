<?php declare(strict_types=1);

namespace App\Application\External;

interface DeliveryService
{
    /**
     * @param string $userID
     * @param string $itemID
     * @return void
     */
    public function process(string $userID, string $itemID): void;
}
