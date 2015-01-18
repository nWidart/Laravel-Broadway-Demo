<?php namespace Modules\Parts\ReadModel;

use Broadway\ReadModel\Projector;
use Broadway\ReadModel\RepositoryInterface;
use Modules\Parts\Events\PartWasManufacturedEvent;

class PartsThatWereManufacturedProjector extends Projector
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function applyPartWasManufactured(PartWasManufacturedEvent $event)
    {
        dd('listened?', $event);
    }
}
