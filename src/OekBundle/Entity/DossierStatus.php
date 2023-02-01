<?php

namespace OekBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_dossier_statussen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "OekAanmelding" = "Aanmelding",
 *     "OekAfsluiting" = "Afsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class DossierStatus
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="Deelnemer")
     * @ORM\JoinColumn(name="oekKlant_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $deelnemer;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    protected $datum;

    /**
     * @ORM\ManyToOne(targetEntity="Verwijzing")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $verwijzing;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }

    public function setDeelnemer(Deelnemer $deelnemer)
    {
        $this->deelnemer = $deelnemer;

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

    public function getVerwijzing()
    {
        return $this->verwijzing;
    }
}
