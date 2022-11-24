<?php declare(strict_types=1);

namespace App\Ports\Cli\Commands;

use DI\Container;
use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    private Container $container;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
