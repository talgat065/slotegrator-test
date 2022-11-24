<?php declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Application\External\DeliveryService;

class DeliveryImplService implements DeliveryService
{

    public function process(string $userID, string $itemID): void
    {
        // do nothing
    }
}
