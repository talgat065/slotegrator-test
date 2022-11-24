<?php declare(strict_types=1);

namespace App\Ports\Cli\Commands;

use App\Application\Services\ItemService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateItemCommand extends BaseCommand
{
    private ItemService $service;

    public function __construct(string $name, ItemService $itemService)
    {
        $this->service = $itemService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('creates a new item')
            ->addArgument('name', InputArgument::REQUIRED, 'Specify name of the item');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $user = $this->service->create($name);
        $response = [
            'id' => $user->getID()->value(),
            'name' => $user->getName()->value(),
        ];
        $output->writeln(json_encode($response));
        return Command::SUCCESS;
    }
}
