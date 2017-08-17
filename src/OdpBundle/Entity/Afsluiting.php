<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_afsluitingen")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\HasLifecycleCallbacks
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "huurder" = "HuurderAfsluiting",
 *     "verhuurder" = "VerhuurderAfsluiting",
 *     "huurverzoek" = "HuurverzoekAfsluiting",
 *     "huuraanbod" = "HuuraanbodAfsluiting",
 *     "huurovereenkomst" = "HuurovereenkomstAfsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class Afsluiting
{
    use TimestampableTrait;

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
     * @ORM\Column(name="active", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $actief = true;

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

    public function isActief()
    {
        return $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = $actief;

        return $this;
    }
}
