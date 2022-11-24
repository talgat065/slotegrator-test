<?php declare(strict_types=1);

namespace App\Ports\Cli\Commands;

use App\Application\External\BankUnavailable;
use App\Application\Services\PrizeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TransferMoneyPrizesCommand extends BaseCommand
{
    private PrizeService $service;

    public function __construct(string $name, PrizeService $prizeService)
    {
        $this->service = $prizeService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('creates a new item')
            ->addOption('batch-count', 'b', InputOption::VALUE_OPTIONAL, 'Number of batch', 1);

        parent::configure();
    }

    /**
     * @throws BankUnavailable
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchCount = (int)$input->getOption('batch-count');
        $transferedCount = $this->service->batchTransfer($batchCount);
        $output->writeln('number of transfered prizes: ' . $transferedCount);
        return Command::SUCCESS;
    }
}
