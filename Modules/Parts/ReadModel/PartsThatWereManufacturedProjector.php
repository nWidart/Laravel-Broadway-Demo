<?php namespace Modules\Parts\ReadModel;

use Broadway\ReadModel\Projector;
use Modules\Parts\Events\PartWasManufacturedEvent;
use Modules\Parts\Repositories\EventStorePartRepository;

class PartsThatWereManufacturedProjector extends Projector
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(EventStorePartRepository $eventStorePartRepository)
    {
        $this->repository = $eventStorePartRepository;
    }

    public function applyPartWasManufactured(PartWasManufacturedEvent $event)
    {
        dd('listened?', $event);
    }
}
