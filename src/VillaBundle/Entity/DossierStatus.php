<?php

namespace VillaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="villa_dossier_statussen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "VillaAanmelding" = "Aanmelding",
 *     "VillaAfsluiting" = "Afsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class DossierStatus
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var Slaper
     *
     * @ORM\ManyToOne(targetEntity="Slaper")
     * @Gedmo\Versioned
     */
    protected $slaper;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    protected $datum;


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

    public function getSlaper()
    {
        return $this->slaper;
    }

    public function setSlaper(Slaper $slaper)
    {
        $this->slaper = $slaper;

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

}
