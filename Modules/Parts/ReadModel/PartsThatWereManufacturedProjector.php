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

    public function __construct(ReadModelPartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function applyPartWasManufacturedEvent(PartWasManufacturedEvent $event)
    {
        $readModel = $this->getReadModel($event->partId, $event->manufacturerName);

        $this->repository->save($readModel);
    }

    private function getReadModel($partId, $manufacturerName)
    {
        $partId = (string) $partId;
        $readModel = $this->repository->find($partId);

        if (null === $readModel) {
            $readModel = new PartsThatWereManufactured($partId, $manufacturerName);
        }

        return $readModel;
    }
}
