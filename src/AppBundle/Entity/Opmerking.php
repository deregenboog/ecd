<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="opmerkingen",
 *     indexes={
 *         @ORM\Index(name="idx_opmerkingen_klant_id", columns={"klant_id"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Opmerking
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="Klant")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $categorie;

    /**
     * @ORM\Column()
     * @Gedmo\Versioned
     */
    private $beschrijving;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $gezien;

    public function getId()
    {
        return $this->id;
    }
}
