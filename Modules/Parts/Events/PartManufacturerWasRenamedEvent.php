<?php namespace Modules\Parts\Events;

use Broadway\Serializer\SerializableInterface;
use Modules\Core\Domain\Identifier;
use Modules\Parts\Entities\PartId;

class PartManufacturerWasRenamedEvent implements SerializableInterface
{
    public $partId;
    public $manufacturerName;

    public function __construct(Identifier $partId, $manufacturerName)
    {
        $this->partId = $partId;
        $this->manufacturerName = $manufacturerName;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        $partId = PartId::fromString($data['partId']);

        return new self($partId, $data['manufacturerName']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'partId' => $this->partId->toString(),
            'manufacturerName' => $this->manufacturerName,
        ];
    }
}
