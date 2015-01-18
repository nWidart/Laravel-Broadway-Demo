<?php namespace Modules\Parts\ReadModel;

use Broadway\ReadModel\Projector;
use Modules\Parts\Events\PartWasManufacturedEvent;
use Modules\Parts\Repositories\ReadModelPartRepository;

class PartsThatWereManufacturedProjector extends Projector
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(ReadModelPartRepository $readModelPartRepository)
    {
        $this->repository = $readModelPartRepository;
    }

    public function applyPartWasManufactured(PartWasManufacturedEvent $event)
    {
        dd('listened?', $event);
    }
}
