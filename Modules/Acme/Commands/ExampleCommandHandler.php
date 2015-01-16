<?php namespace Modules\Acme\Commands;

use Broadway\CommandHandling\CommandHandler;

class ExampleCommandHandler extends CommandHandler
{
    /**
     * Method handling ExampleCommand commands.
     * The fact that this method handles the ExampleCommand is signalled by the
     * convention of the method name: `handle<CommandClassName>`.
     *
     * @param ExampleCommand $command
     */
    public function handleExampleCommand(ExampleCommand $command)
    {
        echo $command->getMessage() . "\n";
    }
}
