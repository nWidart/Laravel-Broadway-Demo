<?php namespace Modules\Parts\Commands;

use Modules\Parts\Entities\PartId;

class RenameManufacturerForPartCommand
{
    public $partId;
    public $manufacturerName;

    public function __construct(PartId $partId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerName = $manufacturerName;
    }
}
