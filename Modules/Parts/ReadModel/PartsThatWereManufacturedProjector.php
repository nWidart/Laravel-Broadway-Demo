<?php namespace Modules\Parts\ReadModel;

use Assert\Assertion;
use Broadway\ReadModel\Projector;
use Modules\Parts\Events\PartManufacturerWasRenamedEvent;
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
        try {
            $readModel = $this->getReadModel($event->partId);
        } catch (\Exception $e) {
            $readModel = new PartsThatWereManufactured($event->partId->toString(), $event->manufacturerName);
        }

        $this->repository->save($readModel);
    }

    public function applyPartManufacturerWasRenamedEvent(PartManufacturerWasRenamedEvent $event)
    {
        $readModel = $this->getReadModel($event->partId);

        $readModel->renameManufacturer($event->manufacturerName);

        $this->repository->save($readModel);
    }

    public function getReadModel($partId)
    {
        $partId = (string) $partId;
        $readModel = $this->repository->find($partId);

        Assertion::isInstanceOf($readModel, PartsThatWereManufactured::class);

        return $readModel;
    }
}
