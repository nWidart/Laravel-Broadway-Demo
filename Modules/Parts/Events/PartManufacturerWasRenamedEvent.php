<?php namespace Modules\Parts\Events;

use Modules\Parts\Entities\PartId;

class PartManufacturerWasRenamedEvent
{
    public $partId;
    public $manufacturerName;

    public function __construct(PartId $partId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerName = $manufacturerName;
    }
}
