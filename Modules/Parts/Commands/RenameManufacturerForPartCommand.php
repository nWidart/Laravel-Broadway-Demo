<?php namespace Modules\Parts\Commands;

use Modules\Core\Domain\Identifier;

class RenameManufacturerForPartCommand
{
    public $partId;
    public $manufacturerName;

    public function __construct(Identifier $partId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerName = $manufacturerName;
    }
}
