<?php namespace Modules\Parts\Events;

use Broadway\Serializer\SerializableInterface;
use Modules\Parts\Entities\PartId;

class PartManufacturerWasRenamedEvent implements SerializableInterface
{
    public $partId;
    public $manufacturerName;

    public function __construct(PartId $partId, $manufacturerName)
    {
        $this->partId = $partId->toString();
        $this->manufacturerName = $manufacturerName;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self($data['partId'], $data['manufacturerName']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'partId' => $this->partId,
            'manufacturerName' => $this->manufacturerName,
        ];
    }
}
