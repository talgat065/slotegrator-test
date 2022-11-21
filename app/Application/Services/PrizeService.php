<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Dto\DrawPrizeRequest;
use App\Application\Repositories\ItemRepository;
use App\Application\Repositories\PrizeRepository;
use App\Application\Repositories\UserRepository;
use App\Domain\Exceptions\UserNotFound;
use App\Domain\RandomNumber;
use App\Domain\SlotMachine;
use App\Domain\ValueObjects\Money;
use Exception;

class PrizeService
{
    private PrizeRepository $prizeRepository;
    private UserRepository $userRepository;
    private ItemRepository $itemRepository;

    public function __construct(PrizeRepository $prizeRepository, UserRepository $userRepository, ItemRepository $itemRepository)
    {
        $this->prizeRepository = $prizeRepository;
        $this->userRepository = $userRepository;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @throws UserNotFound
     */
    public function draw(DrawPrizeRequest $request)
    {
        $user = $this->userRepository->getByID($request->getUserID());
        if ($user === null) {
            throw new UserNotFound('user not found');
        }
        $items = $this->itemRepository->findAll();

        $slotMachine = new SlotMachine(new RandomNumber(), new Money(500), $items);

        $prize = $slotMachine->getPrize($user);
        $this->prizeRepository->persist($prize);

        return $prize;
    }
}
