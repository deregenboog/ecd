<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="zrm_reports")
 * @Gedmo\Loggable
 */
class ZrmReport
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Klant")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @ORM\Column(name="model", type="string", nullable=false)
     * @Gedmo\Versioned
     */
    private $model;

    /**
     * @ORM\Column(name="foreign_key", type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $foreignKey;

    /**
     * @ORM\Column(name="request_module", type="string", nullable=false)
     * @Gedmo\Versioned
     */
    private $requestModule;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $inkomen;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $dagbesteding;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $huisvesting;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $gezinsrelaties;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $geestelijke_gezondheid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $fysieke_gezondheid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $verslaving;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $adl_vaardigheden;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $sociaal_netwerk;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $maatschappelijke_participatie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $justitie;
}
