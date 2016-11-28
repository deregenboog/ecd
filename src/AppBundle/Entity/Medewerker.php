<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="medewerkers")
 */
class Medewerker
{
    use PersoonTrait;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * Dummy property (for PersonTrait to work properly).
     *
     * @var string
     */
    private $roepnaam;

    public function __construct()
    {
        $this->klanten = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
}
