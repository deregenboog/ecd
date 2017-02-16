<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_intakes")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Klant" = "GaKlantIntake", "Vrijwilliger" = "GaVrijwilligerIntake"})
 */
abstract class GaIntake
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $gespreksverslag;

    /**
     * @ORM\Column(name="informele_zorg", type="boolean", nullable=true)
     */
    protected $informeleZorg;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $dagbesteding;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $inloophuis;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hulpverlening;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $ondernemen;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $overdag;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $ontmoeten;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $regelzaken;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean", nullable=true)
     */
    protected $gezinMetKinderen;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $intakedatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $afsluitdatum;

    /**
     * @ORM\ManyToOne(targetEntity="GaAfsluiting")
     * @ORM\JoinColumn(name="groepsactiviteiten_afsluiting_id")
     */
    protected $gaAfsluiting;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }
}
