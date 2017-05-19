<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_dossier_statussen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "OekAanmelding" = "OekAanmelding",
 *     "OekAfsluiting" = "OekAfsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class OekDossierStatus
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var OekKlant
     *
     * @ORM\ManyToOne(targetEntity="OekKlant")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $oekKlant;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $datum;

    /**
     * @ORM\ManyToOne(targetEntity="OekVerwijzing")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $verwijzing;

    public function getId()
    {
        return $this->id;
    }

    public function getOekKlant()
    {
        return $this->oekKlant;
    }

    public function setOekKlant(OekKlant $oekKlant)
    {
        $this->oekKlant = $oekKlant;

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
