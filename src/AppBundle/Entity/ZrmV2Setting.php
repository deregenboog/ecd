<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="zrm_v2_settings")
 * @Gedmo\Loggable
 */
class ZrmV2Setting
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="request_module", type="string", length=50, nullable=false)
     * @Gedmo\Versioned
     */
    private $requestModule;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $financien;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $werk_opleiding;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $tijdsbesteding;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $huisvesting;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $huiselijke_relaties;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $geestelijke_gezondheid;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $lichamelijke_gezondheid;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $middelengebruik;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $basale_adl;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $instrumentele_adl;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $sociaal_netwerk;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $maatschappelijke_participatie;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $justitie;
}
