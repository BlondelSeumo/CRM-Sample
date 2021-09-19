<?php
namespace Database\Seeders\Traits;

use Symfony\Component\Console\Output\ConsoleOutput;

trait ConsoleMessageTrait
{
    public function truncateMessage($class)
    {
        $output = new ConsoleOutput();
        return $output->writeln(
            '<comment>' . class_basename($class) . '</comment>' . ' truncate successful.'
        );
    }

    public function seededMessage($class)
    {
        $output = new ConsoleOutput();
        return $output->writeln(
            class_basename($class) . ' <fg=green>seeded successfully.</>'
        );
    }
}
