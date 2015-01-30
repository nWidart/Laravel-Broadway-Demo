<?php namespace Modules\Parts\Events;

use Broadway\Serializer\SerializableInterface;
use Modules\Parts\Entities\PartId;

class PartWasManufacturedEvent implements SerializableInterface
{
    public $partId;
    public $manufacturerId;
    public $manufacturerName;

    public function __construct(PartId $partId, $manufacturerId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerId = $manufacturerId;
        $this->manufacturerName = $manufacturerName;
    }

    /**
     * @param  array $data
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        $partId = PartId::fromString($data['partId']);

        return new self($partId, $data['manufacturerId'], $data['manufacturerName']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'partId' => $this->partId->toString(),
            'manufacturerId' => $this->manufacturerId->toString(),
            'manufacturerName' => $this->manufacturerName,
        ];
    }
}
