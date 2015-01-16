<?php namespace Modules\Parts\Entities;

use Doctrine\ORM\Mapping as ORM;

class Manufacturer
{
    /**
     * @ORM\OneToMany(targetEntity="Modules\Parts\Entities\Part", mappedBy="manufacturer")
     **/
    private $partId;
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $manufacturerId;
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $manufacturerName;
}
