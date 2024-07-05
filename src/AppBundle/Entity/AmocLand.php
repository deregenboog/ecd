<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="amoc_landen")
 */
class AmocLand
{
    use IdentifiableTrait;

    /**
     * @var Land
     *
     * @ORM\OneToOne(targetEntity="Land")
     */
    private $land;

    public function __construct(Land $land)
    {
        $this->land = $land;
    }

    public function __toString()
    {
        return (string) $this->land;
    }
}
