<?php declare(strict_types=1);

namespace App\Ports\Http\Controllers;

use App\Application\Dto\DrawPrizeRequest;
use App\Application\Services\PrizeService;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class DrawPrizeController extends BaseController
{
    private ServerRequestInterface $request;
    private PrizeService $service;

    public function __construct(ServerRequestInterface $request, PrizeService $service)
    {
        parent::__construct();
        $this->request = $request;
        $this->service = $service;
    }

    public function __invoke()
    {
        $request = new DrawPrizeRequest($this->request->getHeaderLine('X-UserID'));

        try {
            $prize = $this->service->drawPrize($request);
        } catch (Throwable $e) {
            return $this->error([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
        return $this->success('congratulations', [
            'prize_id' => $prize->getId()->value(),
            'money' => $prize->getMoney()->amount(),
            'bonus' => $prize->getBonus()->amount(),
            'item' => $prize->getItem() !== null ? $prize->getItem()->getName()->value() : null,
        ]);
    }
}
