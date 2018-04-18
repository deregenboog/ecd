<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="zrm_reports")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string", length=5)
 * @ORM\DiscriminatorMap({"zrmv1" = "ZrmV1", "zrmv2" = "ZrmV2"})
 * @Gedmo\Loggable
 */
abstract class Zrm
{
    use IdentifiableTrait, TimestampableTrait;

    const ZRM_VERSIONS = [
        ZrmV2::class => '2017-10-05',
        ZrmV1::class => '2010-01-01',
    ];

    /**
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="zrms")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @return Zrm
     */
    public static function create(\DateTime $datum = null)
    {
        if (!$datum) {
            $datum = new \DateTime();
        }

        foreach (self::ZRM_VERSIONS as $model => $date) {
            if ($datum >= new \DateTime($date)) {
                $zrm = new $model();
                break;
            }
        }

        return $zrm;
    }

    public function __construct(Klant $klant = null)
    {
        $this->klant = $klant;
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
}
