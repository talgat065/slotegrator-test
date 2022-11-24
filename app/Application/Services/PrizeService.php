<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Dto\AcceptPrizeRequest;
use App\Application\Dto\DrawPrizeRequest;
use App\Application\Dto\TransferPrizeRequest;
use App\Application\External\BankService;
use App\Application\External\BankUnavailable;
use App\Application\Repositories\ItemRepository;
use App\Application\Repositories\PrizeRepository;
use App\Application\Repositories\UserRepository;
use App\Domain\Exceptions\PrizeNotFound;
use App\Domain\Exceptions\UserNotFound;
use App\Domain\Prize;
use App\Domain\RandomNumber;
use App\Domain\SlotMachine;
use App\Domain\ValueObjects\Money;
use DomainException;

class PrizeService
{
    private PrizeRepository $prizeRepository;
    private UserRepository $userRepository;
    private ItemRepository $itemRepository;
    private BankService $bankService;

    public function __construct(
        PrizeRepository $prizeRepository,
        UserRepository $userRepository,
        ItemRepository $itemRepository,
        BankService $bankService
    ) {
        $this->prizeRepository = $prizeRepository;
        $this->userRepository = $userRepository;
        $this->itemRepository = $itemRepository;
        $this->bankService = $bankService;
    }

    /**
     * @throws UserNotFound
     */
    public function drawPrize(DrawPrizeRequest $request)
    {
        $user = $this->userRepository->getByID($request->getUserID());
        if ($user === null) {
            throw new UserNotFound('user not found');
        }
        $items = $this->itemRepository->findAll();

        $slotMachine = new SlotMachine(new RandomNumber(), new Money(500), $items);

        $prize = $slotMachine->drawPrize($user);
        $this->prizeRepository->persist($prize);

        return $prize;
    }

    public function accept(AcceptPrizeRequest $request)
    {
        $user = $this->userRepository->getByID($request->getUserID());
        if ($user === null) {
            throw new UserNotFound('user not found');
        }

        $prize = $this->prizeRepository->getByID($request->getPrizeID());
        if ($prize === null) {
            throw new PrizeNotFound('prize not found');
        }

        if ($request->isAccept()) {
            $prize->accept($user);
            $this->prizeRepository->persist($prize);
        } else {
            $prize->decline($user);
            $this->prizeRepository->persist($prize);
        }
    }

    /**
     * @throws BankUnavailable
     */
    public function transfer(TransferPrizeRequest $request)
    {
        $user = $this->userRepository->getByID($request->getUserID());
        if ($user === null) {
            throw new UserNotFound('user not found');
        }

        $prize = $this->prizeRepository->getByID($request->getPrizeID());
        if ($prize === null) {
            throw new PrizeNotFound('prize not found');
        }

        if ($prize->getType()->value() === Prize::BONUS) {
            $prize->transferBonusToAccount($user);
            $this->userRepository->persist($user);
        } elseif ($prize->getType()->value() === Prize::MONEY) {
            $prize->transferMoney($user, $request->isConvertationNeeds());
            if ($request->isConvertationNeeds()) {
                $this->userRepository->persist($user);
            } else {
                $this->bankService->transferMoneyToClient($user->getID()->value(), $prize->getMoney()->amount());
            }
        } else {
            throw new DomainException('unable to transfer this kind of prize');
        }

        $this->prizeRepository->persist($prize);
    }
}
