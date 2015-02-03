<?php namespace Modules\Parts\Commands;

use Modules\Core\Domain\Identifier;
use Modules\Parts\Entities\PartId;

class ManufacturePartCommand
{
    public $partId;
    public $manufacturerId;
    public $manufacturerName;

    public function __construct(Identifier $partId, Identifier $manufacturerId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerId = $manufacturerId;
        $this->manufacturerName = $manufacturerName;
    }
}
