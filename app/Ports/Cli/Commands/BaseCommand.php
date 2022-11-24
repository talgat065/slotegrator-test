<?php declare(strict_types=1);

namespace App\Ports\Cli\Commands;

use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
}
