<?php declare(strict_types=1);

namespace App\Ports\Http\Controllers;

use App\Application\Dto\DrawPrizeRequest;
use App\Application\Handlers\PrizeService;
use DI\Container;
use Psr\Http\Message\ServerRequestInterface;

class PrizeController
{
    private ServerRequestInterface $request;
    private PrizeService $prizeService;
    public function __construct(ServerRequestInterface $request, PrizeService $prizeService)
    {
        $this->request = $request;
        $this->prizeService = $prizeService;
    }

    public function __invoke()
    {
//        $c = new Container();
//        $c->get('db');
        $request = new DrawPrizeRequest($this->request->getHeaderLine('X-UserID'));
        $this->prizeService->draw($request);
    }
}
