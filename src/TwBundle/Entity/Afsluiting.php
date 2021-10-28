<?php

namespace TwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_afsluitingen")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\HasLifecycleCallbacks
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "klant" = "KlantAfsluiting",
 *     "verhuurder" = "VerhuurderAfsluiting",
 *     "huurverzoek" = "HuurverzoekAfsluiting",
 *     "huuraanbod" = "HuuraanbodAfsluiting",
 *     "huurovereenkomst" = "HuurovereenkomstAfsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class Afsluiting
{
    use TimestampableTrait, ActivatableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    protected $naam;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $tonen = true;

    public function __toString()
    {
        return $this->getNaam();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }


    public function isTonen()
    {
        return $this->tonen;
    }

    public function setTonen($tonen)
    {
        $this->tonen = $tonen;

        return $this;
    }
}
