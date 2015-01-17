<?php namespace Modules\Parts\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Modules\Parts\Commands\Handlers\PartCommandHandler;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;

class PartsController extends \BaseController
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function manufacture()
    {
        $partId = PartId::generate();
        $manufacturerId = ManufacturerId::generate();

        $command = new ManufacturePartCommand($partId, $manufacturerId, 'BWM');

        //$handler = new PartCommandHandler();
        //$this->commandBus->subscribe($handler);
        $this->commandBus->dispatch($command);

        dd('Something happened ?');
    }
}
