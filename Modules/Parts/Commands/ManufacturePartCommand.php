<?php namespace Modules\Parts\Commands;

use Modules\Core\Domain\Identifier;

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
