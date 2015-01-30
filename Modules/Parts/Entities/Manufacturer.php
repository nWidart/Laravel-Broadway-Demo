<?php namespace Modules\Parts\Entities;

use Broadway\EventSourcing\EventSourcedEntity;
use Doctrine\ORM\Mapping as ORM;
use Modules\Parts\Events\PartManufacturerWasRenamedEvent;

/**
 * @ORM\Entity
 * @ORM\Table(name="manufacturers")
 */
class Manufacturer extends EventSourcedEntity
{
    /**
     * @ORM\OneToMany(targetEntity="Modules\Parts\Entities\Part", mappedBy="manufacturer")
     **/
    private $partId;
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $manufacturerName;

    public function __construct($partId, $id, $manufacturerName)
    {
        $this->partId = $partId;
        $this->id = $id;
        $this->manufacturerName = $manufacturerName;
    }

    public function rename($manufacturerName)
    {
        if ($this->manufacturerName === $manufacturerName) {
            // If the name is not actually different we do not need to do
            // anything here.
            return;
        }
        // This event may also be handled by the aggregate root.
        $this->apply(new PartManufacturerWasRenamedEvent($this->partId->toString(), $manufacturerName));
    }

    protected function applyPartManufacturerWasRenamedEvent(PartManufacturerWasRenamedEvent $event)
    {
        $this->manufacturerName = $event->manufacturerName;
    }
}
