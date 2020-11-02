<?php namespace Modules\Parts\Console;

use Broadway\Domain\DateTime;
use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventStore\EventStoreInterface;
use Illuminate\Console\Command;
use Modules\Parts\Repositories\EventStorePartRepository;

class ReplayPartsCommand extends Command
{
    /**
     * Date until you want to rebuild the parts
     * Edit this property
     *
     * @var string
     */
    protected $limitDate = '2015-04-01 20:00:00';


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asgard:parts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the parts until a specific date. Commands needs to be edited.';

    /** @var EventStorePartRepository */
    private $eventStore;

    /** @var array $eventBuffer */
    private $eventBuffer = [];

    /** @var int $maxBufferSize */
    private $maxBufferSize = 20;

    /** @var EventBusInterface */
    private $eventBus;

    /**
     * ReplayPartsCommand constructor.
     *
     * @param \Modules\Parts\Repositories\EventStorePartRepository $eventStore
     * @param \Broadway\EventHandling\EventBusInterface $eventBus
     */
    public function __construct(EventStorePartRepository $eventStore, EventBusInterface $eventBus)
    {
        parent::__construct();

        $this->eventStore = $eventStore;
        $this->eventBus   = $eventBus;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Rebuilding stuff...');

        $streamIds = $this->eventStore->getStreamIds();
        $this->process($streamIds);

        $this->comment('Finished rebuilding.');
    }

    /**
     * @param $streamIds
     */
    private function process($streamIds)
    {
        foreach ($streamIds as $id) {
            $this->rebuildStream($id);
            $this->publishEvents();
        }
    }

    /**
     * @param $id
     */
    private function rebuildStream($id)
    {
        /** @var EventStoreInterface $eventStore */
        $eventStore = app(EventStoreInterface::class);
        $stream     = $eventStore->load($id);

        foreach ($stream->getIterator() as $event) {
            $limit          = DateTime::fromString($this->limitDate);
            $recordedOnDate = $event->getRecordedOn();
            if ($recordedOnDate->comesAfter($limit)) {
                continue;
            }
            $this->addEventToBuffer($event);
            $this->guardBufferNotFull();
        }
    }


    private function publishEvents()
    {
        $this->eventBus->publish(new DomainEventStream($this->eventBuffer));
        $this->clearEventBuffer();
    }

    /**
     * @param $event
     */
    private function addEventToBuffer($event)
    {
        $this->eventBuffer[] = $event;
    }

    /**
     * @return bool
     */
    private function bufferLimitReached()
    {
        return count($this->eventBuffer) > $this->maxBufferSize;
    }


    private function clearEventBuffer()
    {
        $this->eventBuffer = [];
    }


    private function guardBufferNotFull()
    {
        if ($this->bufferLimitReached()) {
            $this->publishEvents();
        }
    }
}
