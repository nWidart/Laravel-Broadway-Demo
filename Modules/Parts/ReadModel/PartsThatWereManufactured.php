<?php namespace Modules\Parts\ReadModel;

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

class PartsThatWereManufactured implements ReadModelInterface, SerializableInterface
{
    /**
     * @var int
     */
    private $manufacturedPartId;
    /**
     * @var string
     */
    private $manufacturerName;

    public function __construct($manufacturedPartId, $manufacturerName)
    {
        $this->manufacturedPartId = $manufacturedPartId;
        $this->manufacturerName = $manufacturerName;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->manufacturedPartId;
    }

    public function renameManufacturer($manufacturerName)
    {
        $this->manufacturerName = $manufacturerName;
    }

    /**
     * @param  array $data
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self($data['manufacturedPartId'], $data['manufacturerName']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'manufacturedPartId' => $this->manufacturedPartId,
            'manufacturerName' => $this->manufacturerName
        ];
    }
}
