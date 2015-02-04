<?php namespace Modules\Parts\Entities;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Modules\Parts\Events\PartWasManufacturedEvent;

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
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Modules\Parts\Entities\Manufacturer", inversedBy="partId")
     **/
    private $manufacturer;

    /**
     * Factory method to create a part.
     *
     * @param $partId
     * @param $manufacturerId
     * @param $manufacturerName
     * @return Part
     */
    public static function manufacture($partId, $manufacturerId, $manufacturerName)
    {
        $part = new Part();
        // After instantiation of the object we apply the "PartWasManufacturedEvent".
        $part->apply(new PartWasManufacturedEvent($partId, $manufacturerId, $manufacturerName));

        return $part;
    }

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return $this->partId;
    }

    public function renameManufacturer($manufacturerName)
    {
        $this->manufacturer->rename($manufacturerName);
    }

    public function remove()
    {
        // $this->apply(new PartWasRemovedEvent($this->partId));
    }

    public function applyPartWasManufacturedEvent(PartWasManufacturedEvent $event)
    {
        $this->partId = $event->partId;
        // We create the entity in our event handler so that it will be created
        // when the aggregate root is reconstituted from an event stream. Once
        // the child entity is instantiated and returned by getChildEntities()
        // it can emit and apply events itself.
        $this->manufacturer = new Manufacturer(
            $event->partId,
            $event->manufacturerId,
            $event->manufacturerName
        );
    }

    protected function getChildEntities()
    {
        // Since the aggregate root always handles the events first we can rely
        // on $this->manufacturer being set by the time the child entities are
        // requested *provided* PartWasManufacturedEvent is the first event in
        // the event stream.
        return [$this->manufacturer];
    }
}
