<?php namespace Modules\Parts\Repositories;

use Broadway\Domain\DomainEventStreamInterface;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStoreInterface;
use Doctrine\DBAL\Connection;
use Modules\Parts\Entities\Part;

class MysqlEventStorePartRepository extends EventSourcingRepository implements EventStorePartRepository
{
    /**
     * @var EventStoreInterface
     */
    protected $eventStore;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(EventStoreInterface $eventStore, EventBusInterface $eventBus, Connection $connection)
    {
        $this->eventStore = $eventStore;
        $this->connection = $connection;

        parent::__construct($eventStore, $eventBus, Part::class, new PublicConstructorAggregateFactory());
    }

    public function append($id, DomainEventStreamInterface $eventStream)
    {
        $this->eventStore->append($id, $eventStream);
    }

    /**
     * {@inheritDoc}
     */
    public function getStreamIds()
    {
        $statement = $this->connection->prepare('SELECT DISTINCT uuid FROM ' . config('broadway.event-store.table'));
        $statement->execute();

        return array_map(
            function($row) {
                return $row['uuid'];
            },
            $statement->fetchAll()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function load($id)
    {
        return $this->eventStore->load($id);
    }
}
