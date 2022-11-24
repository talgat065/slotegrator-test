<?php declare(strict_types=1);

namespace App\Ports\Http\Controllers;

use App\Application\Dto\DrawPrizeRequest;
use App\Application\Repositories\PrizeRepository;
use App\Domain\Shared\UUID;
use Psr\Http\Message\ServerRequestInterface;

class PrizesListController extends BaseController
{
    private ServerRequestInterface $request;
    private PrizeRepository $repository;

    public function __construct(ServerRequestInterface $request, PrizeRepository $repository)
    {
        parent::__construct();
        $this->request = $request;
        $this->repository = $repository;
    }

    public function __invoke()
    {
        $request = new DrawPrizeRequest($this->request->getHeaderLine('X-UserID'));

        $prizes = $this->repository->prizesList(new UUID($request->getUserID()));

        $data = [];
        foreach ($prizes as $prize) {
            $item = $prize->getItem();
            $data[] = [
                'prize_id' => $prize->getId()->value(),
                'type' => $prize->getType()->value(),
                'money' => $prize->getMoney()->amount(),
                'bonus' => $prize->getBonus()->amount(),
                'item' => $item !== null ? [
                    'id' => $prize->getItem()->getId()->value(),
                    'name' => $prize->getItem()->getName()->value(),
                ] : null,
            ];
        }
        return $this->success('prizes list', $data);
    }
}
