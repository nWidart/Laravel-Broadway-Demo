<?php namespace Modules\Parts\Entities;

use Broadway\EventSourcing\EventSourcedEntity;
use Modules\Parts\Events\PartManufacturerWasRenamedEvent;

class Manufacturer extends EventSourcedEntity
{
    private $partId;
    private $id;
    private $manufacturerName;

    public function __construct($partId, $id, $manufacturerName)
    {
        $this->partId = $partId;
        $this->id = $id;
        $this->manufacturerName = $manufacturerName;
    }

    public function rename($manufacturerName)
    {
        if ($this->manufacturerName === $manufacturerName) {
            // If the name is not actually different we do not need to do
            // anything here.
            return;
        }
        // This event may also be handled by the aggregate root.
        $this->apply(new PartManufacturerWasRenamedEvent($this->partId, $manufacturerName));
    }

    protected function applyPartManufacturerWasRenamedEvent(PartManufacturerWasRenamedEvent $event)
    {
        $this->manufacturerName = $event->manufacturerName;
    }
}
