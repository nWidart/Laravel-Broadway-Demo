<?php namespace Modules\Parts\Http\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Illuminate\Routing\Controller;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Commands\RenameManufacturerForPartCommand;
use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;
use Modules\Parts\Repositories\ReadModelPartRepository;

class PartsController extends Controller
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

    public function renameManufacturer($partId, $name)
    {
        $partId = PartId::fromString($partId);

        $command = new RenameManufacturerForPartCommand($partId, $name);
        $this->commandBus->dispatch($command);

        dd('Part was renamed');
    }
}
