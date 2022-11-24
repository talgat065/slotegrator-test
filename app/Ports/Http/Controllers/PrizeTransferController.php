<?php declare(strict_types=1);

namespace App\Ports\Http\Controllers;

use App\Application\Dto\AcceptPrizeRequest;
use App\Application\Dto\TransferPrizeRequest;
use App\Application\Services\PrizeService;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class PrizeTransferController extends BaseController
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
        $queryParams = $this->request->getQueryParams();
        if (!isset($queryParams['prize_id'])) {
            return $this->error([
                'status' => 'failed',
                'message' => 'field prize_id is required'
            ]);
        }

        $request = new TransferPrizeRequest(
            $this->request->getHeaderLine('X-UserID'),
            $queryParams['prize_id'],
            (bool)$queryParams['needs_convertation']
        );

        try {
            $this->service->transfer($request);
        } catch (Throwable $e) {
            return $this->error([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
        return $this->success('processed', null);
    }
}
