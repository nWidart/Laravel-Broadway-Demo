<?php namespace Modules\Parts\ReadModel;

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

class PartsThatWereManufactured implements ReadModelInterface, SerializableInterface
{
    private $manufacturedPartId;

    public function __construct($manufacturedPartId)
    {
        $this->manufacturedPartId = $manufacturedPartId;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->manufacturedPartId;
    }

    /**
     * @param array $data
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self($data['manufacturedPartId']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return ['manufacturedPartId' => $this->manufacturedPartId];
    }
}
