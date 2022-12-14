<?php declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Dto\AcceptPrizeRequest;
use App\Application\Dto\DeliverPrizeRequest;
use App\Application\Dto\DrawPrizeRequest;
use App\Application\Dto\TransferPrizeRequest;
use App\Application\External\BankService;
use App\Application\External\BankUnavailable;
use App\Application\External\DeliveryService;
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
    private DeliveryService $deliveryService;

    public function __construct(
        PrizeRepository $prizeRepository,
        UserRepository $userRepository,
        ItemRepository $itemRepository,
        BankService $bankService,
        DeliveryService $deliveryService
    ) {
        $this->prizeRepository = $prizeRepository;
        $this->userRepository = $userRepository;
        $this->itemRepository = $itemRepository;
        $this->bankService = $bankService;
        $this->deliveryService = $deliveryService;
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
        } else {
            $prize->decline($user);
        }
        $this->prizeRepository->persist($prize);
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

    public function delivery(DeliverPrizeRequest $request)
    {
        $user = $this->userRepository->getByID($request->getUserID());
        if ($user === null) {
            throw new UserNotFound('user not found');
        }

        $prize = $this->prizeRepository->getByID($request->getPrizeID());
        if ($prize === null) {
            throw new PrizeNotFound('prize not found');
        }

        $prize->sendOnDelivery($user);
        $this->deliveryService->process($user->getID()->value(), $prize->getItem()->getId()->value());
        $this->prizeRepository->persist($prize);
    }

    /**
     * Transfers prize money's to user accounts.
     * Returns number of processed prizes.
     * @param int $batchCount
     * @return int
     * @throws BankUnavailable
     */
    public function batchTransfer(int $batchCount): int
    {
        $prizes = $this->prizeRepository->findUnprocessedMoneyPrizes($batchCount);

        foreach ($prizes as $prize) {
            $prize->transferMoney($prize->getUser(), false);

            $this->bankService->transferMoneyToClient(
                $prize->getUser()->getID()->value(),
                $prize->getMoney()->amount()
            );

            $this->prizeRepository->persist($prize);
        }

        return sizeof($prizes);
    }
}
