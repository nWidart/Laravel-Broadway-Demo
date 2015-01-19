<?php namespace Modules\Parts\Entities;

use Modules\Core\Domain\Identifier;
use Modules\Core\Domain\UuidIdentifier;
use Rhumsaa\Uuid\Uuid;

class PartId extends UuidIdentifier implements Identifier
{
    /**
     * @var Uuid
     */
    protected $value;

    /**
     * Create a new Identifier
     *
     * @param Uuid $value
     */
    public function __construct(Uuid $value)
    {
        $this->value = $value;
    }
}
