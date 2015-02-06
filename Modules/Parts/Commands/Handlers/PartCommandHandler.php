<?php namespace Modules\Parts\Commands\Handlers;

use Broadway\CommandHandling\CommandHandler;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Commands\RemovePartCommand;
use Modules\Parts\Commands\RenameManufacturerForPartCommand;
use Modules\Parts\Entities\Part;
use Modules\Parts\Repositories\MysqlEventStorePartRepository;

class PartCommandHandler extends CommandHandler
{
    /**
     * @var MysqlEventStorePartRepository
     */
    private $repository;

    public function __construct(MysqlEventStorePartRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * A new part aggregate root is created and added to the repository.
     *
     * @param ManufacturePartCommand $command
     */
    protected function handleManufacturePartCommand(ManufacturePartCommand $command)
    {
        $part = Part::manufacture($command->partId, $command->manufacturerId, $command->manufacturerName);
        $this->repository->add($part);
    }

    /**
     * An existing part aggregate root is loaded and renameManufacturerTo() is
     * called.
     *
     * @param RenameManufacturerForPartCommand $command
     */
    protected function handleRenameManufacturerForPartCommand(RenameManufacturerForPartCommand $command)
    {
        $part = $this->repository->load($command->partId);
        $part->renameManufacturer($command->manufacturerName);
        $this->repository->add($part);
    }

    protected function handleRemovePartCommand(RemovePartCommand $command)
    {
        $part = $this->repository->load($command->partId);
        $part->remove();
        $this->repository->add($part);
    }
}
