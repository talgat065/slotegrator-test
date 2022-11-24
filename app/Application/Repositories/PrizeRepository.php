<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Domain\Prize;
use App\Domain\Shared\UUID;

interface PrizeRepository
{
    /**
     * Saves prize to storage.
     * @param Prize $prize
     * @return void
     */
    public function persist(Prize $prize): void;

    /**
     * Returns list of user's prizes
     * @param UUID $userID
     * @return Prize[]
     */
    public function prizesList(UUID $userID): array;

    /**
     * @param string $id
     * @return Prize|null
     */
    public function getByID(string $id): ?Prize;

    /**
     * Finds unprocessed money typed prizes.
     * @return Prize[]
     */
    public function findUnprocessedMoneyPrizes(int $batchCount): array;
}
