<?php namespace Modules\Parts\Http\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
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

    /**
     * Display a listing of all the manufactured parts.
     * This reads everything from the read model
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $parts = $this->readModelPartRepository->findAll();

        return view('parts::index', compact('parts'));
    }

    /**
     * Create a Part in the event store as well as in the read model
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $partId = PartId::generate();
        $manufacturerId = ManufacturerId::generate();

        $command = new ManufacturePartCommand($partId, $manufacturerId, $request->get('name'));
        $this->commandBus->dispatch($command);

        return Redirect::route('parts.index')->with('success', 'Part successfully created.');
    }

    public function renameManufacturer($partId, $name)
    {
        $partId = PartId::fromString($partId);

        $command = new RenameManufacturerForPartCommand($partId, $name);
        $this->commandBus->dispatch($command);

        dd('Part was renamed');
    }
}
