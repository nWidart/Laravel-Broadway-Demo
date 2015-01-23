<?php namespace Modules\Parts\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;
use Modules\Parts\Repositories\ReadModelPartRepository;

class PartsController extends \BaseController
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;
    /**
     * @var ReadModelPartRepository
     */
    private $readModelPartRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        ReadModelPartRepository $readModelPartRepository
    ) {
        $this->commandBus = $commandBus;
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
        $parts = $this->readModelPartRepository->findAll();

        dd('read model', $parts);
    }
}
