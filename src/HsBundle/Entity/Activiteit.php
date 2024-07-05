<?php

namespace HsBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="hs_activiteiten")
 *
 * @Gedmo\Loggable
 *
 * @ORM\HasLifecycleCallbacks
 */
class Activiteit
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;
    use ActivatableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    /**
     * @ORM\ManyToMany(targetEntity="Klus", mappedBy="activiteiten")
     */
    private $klussen;

    public function __construct($naam = null)
    {
        $this->naam = $naam;
        $this->klussen = new ArrayCollection();
        $this->created = $this->modified = new \DateTime();
    }

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function addKlus(Klus $klus)
    {
        $this->klussen[] = $klus;

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->klussen);
    }
}
