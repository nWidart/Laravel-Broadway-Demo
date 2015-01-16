<?php namespace Modules\Parts\Events;

use Modules\Parts\Entities\ManufacturerId;
use Modules\Parts\Entities\PartId;

class PartWasManufacturedEvent
{
    public $partId;
    public $manufacturerId;
    public $manufacturerName;

    public function __construct(PartId $partId, ManufacturerId $manufacturerId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerId = $manufacturerId;
        $this->manufacturerName = $manufacturerName;
    }
}
