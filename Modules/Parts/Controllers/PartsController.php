<?php namespace Modules\Parts\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Broadway\EventHandling\EventBusInterface;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;
use Modules\Parts\Repositories\EventStorePartRepository;
use Modules\Parts\Repositories\ReadModelPartRepository;

class PartsController extends \BaseController
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;
    /**
     * @var EventStorePartRepository
     */
    private $eventStorePartRepository;
    /**
     * @var ReadModelPartRepository
     */
    private $readModelPartRepository;
    /**
     * @var EventBusInterface
     */
    private $eventBus;

    public function __construct(
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus,
        EventStorePartRepository $eventStorePartRepository,
        ReadModelPartRepository $readModelPartRepository
    ) {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->eventStorePartRepository = $eventStorePartRepository;
        $this->readModelPartRepository = $readModelPartRepository;
    }

    public function manufacture()
    {
        $partId = PartId::generate();
        $manufacturerId = ManufacturerId::generate();

        $command = new ManufacturePartCommand($partId, $manufacturerId, 'BMW');
        $this->commandBus->dispatch($command);

        dd('Part was stored in event store');
    }

    public function manufacturedParts($partId)
    {
        $part = $this->readModelPartRepository->find($partId);

        dd('read model', $part);
    }
}
