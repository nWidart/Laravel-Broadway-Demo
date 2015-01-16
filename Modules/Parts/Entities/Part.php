<?php namespace Modules\Parts\Entities;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parts")
 */
class Part extends EventSourcedAggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $partId;

    private $manufacturer;

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        // TODO: Implement getAggregateRootId() method.
    }
}
