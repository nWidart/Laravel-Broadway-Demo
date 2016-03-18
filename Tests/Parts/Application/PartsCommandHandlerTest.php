<?php namespace Asgard\Tests\Parts\Application;

use Broadway\CommandHandling\CommandHandlerInterface;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventStore\EventStoreInterface;
use Broadway\UuidGenerator\Rfc4122\Version4Generator;
use Doctrine\DBAL\Connection;
use Modules\Parts\Commands\Handlers\PartCommandHandler;
use Modules\Parts\Commands\ManufacturePartCommand;
use Modules\Parts\Commands\RenameManufacturerForPartCommand;
use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;
use Modules\Parts\Events\PartManufacturerWasRenamedEvent;
use Modules\Parts\Events\PartWasManufacturedEvent;
use Modules\Parts\Repositories\MysqlEventStorePartRepository;

class PartsCommandHandlerTest extends CommandHandlerScenarioTestCase
{

    public function setUp()
    {
        parent::setUp();
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
        $app = $this->createApplication();
        $repository = new MysqlEventStorePartRepository($eventStore, $eventBus, $app[Connection::class]);

        return new PartCommandHandler($repository);
    }

    /**
     * @test
     */
    public function it_can_manufacture()
    {
        $partId = PartId::generate();
        $manufacturerId = ManufacturerId::generate();

        $this->scenario
            ->withAggregateId($partId->toString())
            ->given([])
            ->when(new ManufacturePartCommand($partId, $manufacturerId, 'Acme, Inc'))
            ->then([new PartWasManufacturedEvent($partId, $manufacturerId, 'Acme, Inc')]);
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

    /**
     * Creates the application.
     * @return \Illuminate\Foundation\Application
     */
    private function createApplication()
    {
        $app = require __DIR__ . '/../../../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
