<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="InloopBundle\Repository\DossierStatusRepository")
 *
 * @ORM\Table(name="inloop_dossier_statussen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 * @ORM\DiscriminatorColumn(name="class", type="string")
 *
 * @ORM\DiscriminatorMap({
 *     "Aanmelding" = "Aanmelding",
 *     "Afsluiting" = "Afsluiting"
 * })
 *
 * @Gedmo\Loggable
 */
abstract class DossierStatus
{
    use TimestampableTrait;
    use OptionalMedewerkerTrait;

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="statussen")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    protected $datum;

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

    public function __construct(?Medewerker $medewerker = null)
    {
        $this->medewerker = $medewerker;
        $this->datum = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum($datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function isAangemeld()
    {
        return $this instanceof Aanmelding;
    }

    public function isAfgesloten()
    {
        return $this instanceof Afsluiting;
    }
}
