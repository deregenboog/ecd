<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="OekraineBundle\Repository\DossierStatusRepository")
 * @ORM\Table(name="oekraine_dossier_statussen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "Aanmelding" = "Aanmelding",
 *     "Afsluiting" = "Afsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class DossierStatus
{
    use TimestampableTrait, OptionalMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Bezoeker
     *
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Bezoeker", inversedBy="statussen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $bezoeker;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $datum;

    public function __construct(Bezoeker $bezoeker, Medewerker $medewerker = null)
    {
        $this->bezoeker = $bezoeker;
        $bezoeker->setDossierStatus($this);

        $this->medewerker = $medewerker;
        $this->datum = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Bezoeker
     */
    public function getBezoeker(): Bezoeker
    {
        return $this->bezoeker;
    }

    /**
     * @param Bezoeker $bezoeker
     * @return DossierStatus
     */
    public function setBezoeker(Bezoeker $bezoeker): DossierStatus
    {
        $this->bezoeker = $bezoeker;
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
