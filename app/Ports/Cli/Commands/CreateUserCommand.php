<?php declare(strict_types=1);

namespace App\Ports\Cli\Commands;

use App\Application\Services\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends BaseCommand
{
    private UserService $service;

    public function __construct(string $name, UserService $userService)
    {
        $this->service = $userService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('creates a new user')
            ->addArgument('name', InputArgument::REQUIRED, 'Specify name of the user');
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
