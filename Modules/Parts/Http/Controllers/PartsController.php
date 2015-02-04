<?php namespace Modules\Parts\Http\Controllers;

use Broadway\CommandHandling\CommandBusInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Commands\RemovePartCommand;
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

    /**
     * Injecting the command bus to send commands
     * And a read model to list all manufactured parts
     * @param CommandBusInterface $commandBus
     * @param ReadModelPartRepository $readModelPartRepository
     */
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

    /**
     * Update a part aggregate
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $partId = PartId::fromString($request->get('pk'));

        $command = new RenameManufacturerForPartCommand($partId, $request->get('value'));
        $this->commandBus->dispatch($command);

        return ['updated' => true];
    }

    public function destroy(Request $request)
    {
        $partId = PartId::fromString($request->get('partId'));

        $command = new RemovePartCommand($partId);
        $this->commandBus->dispatch($command);

        return Redirect::route('parts.index')->with('success', 'Part successfully deleted.');
    }
}
