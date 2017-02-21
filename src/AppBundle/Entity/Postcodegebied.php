<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postcodegebieden")
 */
class Postcodegebied
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $postcodegebied;

    /**
     * @ORM\Column(type="integer")
     */
    private $van;

    /**
     * @ORM\Column(type="integer")
     */
    private $tot;

    public function __toString()
    {
        return $this->postcodegebied;
    }
}
