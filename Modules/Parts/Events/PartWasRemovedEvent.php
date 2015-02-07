<?php namespace Modules\Parts\Events;

use Broadway\Serializer\SerializableInterface;
use Modules\Parts\Entities\PartId;

class PartWasRemovedEvent implements SerializableInterface
{
    /**
     * @var
     */
    public $partId;

    public function __construct(PartId $partId)
    {
        $this->partId = $partId;
    }

    /**
     * @param array $data
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        $partId = PartId::fromString($data['partId']);

        return new self($partId);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return ['partId' => $this->partId->toString()];
    }
}
