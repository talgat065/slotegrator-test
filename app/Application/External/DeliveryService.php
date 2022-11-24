<?php declare(strict_types=1);

namespace App\Application\External;

interface DeliveryService
{
    /**
     * Orders a delivery for item type prize.
     * @param string $userID
     * @param string $itemID
     * @return void
     */
    public function process(string $userID, string $itemID): void;
}
