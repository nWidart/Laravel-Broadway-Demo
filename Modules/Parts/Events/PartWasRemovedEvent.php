<?php namespace Modules\Parts\Events;

use Broadway\Serializer\SerializableInterface;

class PartWasRemovedEvent implements SerializableInterface
{
    /**
     * @var
     */
    public $partId;

    public function __construct($partId)
    {
        $this->partId = $partId;
    }

    /**
     * @param array $data
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self($data['partId']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return ['partId' => $this->partId];
    }
}
