<?php namespace Modules\Parts\Events;

class PartManufacturerWasRenamedEvent
{
    public $partId;
    public $manufacturerName;

    public function __construct($partId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerName = $manufacturerName;
    }
}
