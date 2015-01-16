<?php namespace Asgard\Tests\Parts\Application;

use Broadway\CommandHandling\CommandHandlerInterface;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventStore\EventStoreInterface;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Modules\Parts\Commands\Handlers\PartCommandHandler;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Commands\RenameManufacturerForPartCommand;
use Modules\Parts\Entities\PartId;
use Modules\Parts\Events\PartManufacturerWasRenamedEvent;
use Modules\Parts\Events\PartWasManufacturedEvent;
use Modules\Parts\Repositories\PartRepository;
use Rhumsaa\Uuid\Uuid;

class PartsCommandHandlerTest extends CommandHandlerScenarioTestCase
{
    /**
     * @var Version4Generator
     */
    private $generator;

    public function setUp()
    {
        parent::setUp();
        $this->generator = new Version4Generator();
    }

    /**
     * Create a command handler for the given scenario test case.
     *
     * @param EventStoreInterface $eventStore
     * @param EventBusInterface $eventBus
     * @return CommandHandlerInterface
     */
    protected function createCommandHandler(EventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        $repository = new PartRepository($eventStore, $eventBus);

        return new PartCommandHandler($repository);
    }

    /**
     * @test
     */
    public function it_can_manufacture()
    {
        $id = PartId::generate();
        $this->scenario
            ->withAggregateId($id)
            ->given([])
            ->when(new ManufacturePartCommand($id, 'acme', 'Acme, Inc'))
            ->then([new PartWasManufacturedEvent($id, 'acme', 'Acme, Inc')]);
    }

    /**
     * @test
     */
    public function it_can_rename_manufacturer()
    {
        $id = PartId::generate();
        $this->scenario
            ->withAggregateId($id)
            ->given([new PartWasManufacturedEvent($id, 'acme', 'Acme, Inc')])
            ->when(new RenameManufacturerForPartCommand($id, 'Acme, Inc.'))
            ->then([new PartManufacturerWasRenamedEvent($id, 'Acme, Inc.')]);
    }

    /**
     * @test
     */
    public function it_does_not_rename_manufacturer_to_the_same_name()
    {
        $id = PartId::generate();
        $this->scenario
            ->withAggregateId($id)
            ->given([
                new PartWasManufacturedEvent($id, 'acme', 'Acme, Inc'),
                new PartWasManufacturedEvent($id, 'acme', 'Acme, Inc.'),
            ])
            ->when(new RenameManufacturerForPartCommand($id, 'Acme, Inc.'))
            ->then([]);
    }
}
