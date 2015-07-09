<?php namespace Modules\Parts\Console;

use Broadway\Domain\DateTime;
use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBusInterface;
use Illuminate\Console\Command;
use Modules\Parts\Repositories\EventStorePartRepository;

class ReplayPartsCommand extends Command
{
    /**
     * Date until you want to rebuild the parts
     * Edit this property
     * @var string
     */
    protected $limitDate = '2015-01-30 20:00:00';
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'asgard:parts';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Rebuild the parts until a specific date. Commands needs to be edited.';
    /**
     * @var EventStorePartRepository
     */
    private $eventStore;
    private $eventBuffer;
    private $maxBufferSize = 20;
    /**
     * @var EventBusInterface
     */
    private $eventBus;

    public function __construct(EventStorePartRepository $eventStore, EventBusInterface $eventBus)
    {
        parent::__construct();

        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Rebuilding stuff...');

        $streamIds = $this->eventStore->getStreamIds();
        $this->process($streamIds);

        $this->comment('Finished rebuilding.');
    }

    private function process($streamIds)
    {
        foreach ($streamIds as $id) {
            $this->rebuildStream($id);
            $this->publishEvents();
        }
    }

    private function rebuildStream($id)
    {
        /** @var \Broadway\EventStore\EventStoreInterface $eventStore */
        $eventStore = app('Broadway\EventStore\EventStoreInterface');
        $stream = $eventStore->load($id);

        foreach ($stream->getIterator() as $event) {
            $limit = DateTime::fromString($this->limitDate);
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

    private function addEventToBuffer($event)
    {
        $this->eventBuffer[] = $event;
    }

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
