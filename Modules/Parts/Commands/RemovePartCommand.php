<?php namespace Modules\Parts\Commands;

use Modules\Core\Domain\Identifier;

class RemovePartCommand
{
    public $partId;

    public function __construct(Identifier $partId)
    {
        $this->partId = $partId;
    }
}
