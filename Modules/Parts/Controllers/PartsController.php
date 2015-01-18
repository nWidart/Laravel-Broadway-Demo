<?php namespace Modules\Parts\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Modules\Parts\Commands\Handlers\PartCommandHandler;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;
use Modules\Parts\Repositories\EventStorePartRepository;

class PartsController extends \BaseController
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;
    /**
     * @var EventStorePartRepository
     */
    private $partRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        EventStorePartRepository $partRepository
    ) {
        $this->commandBus = $commandBus;
        $this->partRepository = $partRepository;
    }

    public function manufacture()
    {
        $partId = PartId::generate();
        $manufacturerId = ManufacturerId::generate();

        $command = new ManufacturePartCommand($partId, $manufacturerId, 'BMW');

        $handler = new PartCommandHandler($this->partRepository);
        $this->commandBus->subscribe($handler);
        $this->commandBus->dispatch($command);

        dd('Something happened ?');
    }
}
