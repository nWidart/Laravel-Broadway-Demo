<?php namespace Modules\Parts\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventHandling\EventBusInterface;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;
use Modules\Parts\Repositories\EventStorePartRepository;
use Modules\Parts\Repositories\ReadModelPartRepository;
use stdClass;

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

    public function manufacturedParts()
    {
        $part = $this->readModelPartRepository->find('1429ee5f-b6f8-4d42-9059-5f84d4bfc683');
        dd('read model', $part);
    }
}
