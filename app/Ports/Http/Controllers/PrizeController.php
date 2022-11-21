<?php declare(strict_types=1);

namespace App\Ports\Http\Controllers;

use App\Application\Dto\DrawPrizeRequest;
use App\Application\Handlers\PrizeService;
use App\Domain\Exceptions\UserNotFound;
use DI\Container;
use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;

class PrizeController extends BaseController
{
    private ServerRequestInterface $request;
    private PrizeService $prizeService;

    public function __construct(ServerRequestInterface $request, PrizeService $prizeService)
    {
        parent::__construct();
        $this->request = $request;
        $this->prizeService = $prizeService;
    }

    public function __invoke()
    {
        $request = new DrawPrizeRequest($this->request->getHeaderLine('X-UserID'));

        try {
            $prize = $this->prizeService->draw($request);
        } catch (\Throwable $e) {
            return $this->error([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
        return $this->success('congratulations', [
            'user_id' => $request->getUserID(),
            'money' => $prize->getMoney()->amount(),
            'bonus' => $prize->getBonus()->amount(),
            'item' => $prize->getItem() !== null ? $prize->getItem()->getName() : null,
        ]);
    }
}
