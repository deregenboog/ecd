<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Legitimatie;
use InloopBundle\Entity\Locatie;

/**
 * Hi5Intakes.
 *
 * @ORM\Table(
 *     name="hi5_intakes",
 *     indexes={@ORM\Index(name="idx_hi5_intakes_klant_id", columns={"klant_id"})}
 * )
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Hi5Intake
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @ORM\Column(name="datum_intake", type="date", nullable=true)
     */
    private $datumIntake;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Verblijfsstatus")
     */
    private $verblijfstatus;

    /**
     * @ORM\Column(name="postadres", type="string", length=255, nullable=true)
     */
    private $postadres;

    /**
     * @ORM\Column(name="postcode", type="string", length=6, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(name="woonplaats", type="string", length=255, nullable=true)
     */
    private $woonplaats;

    /**
     * @ORM\Column(name="verblijf_in_NL_sinds", type="date", nullable=true)
     */
    private $verblijfInNlSinds;

    /**
     * @ORM\Column(name="verblijf_in_amsterdam_sinds", type="date", nullable=true)
     */
    private $verblijfInAmsterdamSinds;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Legitimatie")
     */
    private $legitimatie;

    /**
     * @ORM\Column(name="legitimatie_nummer", type="string", length=255, nullable=true)
     */
    private $legitimatieNummer;

    /**
     * @ORM\Column(name="legitimatie_geldig_tot", type="date", nullable=true)
     */
    private $legitimatieGeldigTot;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Verslaving")
     * @ORM\JoinTable(
     *     name="hi5_intakes_verslavingen",
     *     joinColumns={@ORM\JoinColumn(name="hi5_intake_id")}
     * )
     */
    private $verslavingen;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Gebruikswijze")
     * @ORM\Jointable(
     *     name="hi5_intakes_verslavingsgebruikswijzen",
     *     joinColumns={@ORM\JoinColumn(name="hi5_intake_id")}
     * )
     */
    private $verslavingsgebruikswijzen;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Frequentie")
     */
    private $verslavingsfrequentie;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Periode")
     */
    private $verslavingsperiode;

    /**
     * @ORM\Column(name="verslaving_overig", type="string", length=255, nullable=true)
     */
    private $verslavingOverig;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Woonsituatie")
     */
    private $woonsituatie;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Instantie")
     * @ORM\Jointable(
     *     name="hi5_intakes_instanties",
     *     joinColumns={@ORM\JoinColumn(name="hi5_intake_id")}
     * )
     */
    private $instanties;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     */
    private $locatie1;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     */
    private $locatie2;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     */
    private $locatie3;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     */
    private $werklocatie;

    /**
     * @var bool
     *
     * @ORM\Column(name="mag_gebruiken", type="boolean", nullable=true)
     */
    private $magGebruiken;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Inkomen")
     * @ORM\Jointable(
     *     name="hi5_intakes_inkomens",
     *     joinColumns={@ORM\JoinColumn(name="hi5_intake_id")}
     * )
     */
    private $inkomens;

    /**
     * @ORM\Column(name="inkomen_overig", type="string", length=255, nullable=true)
     */
    private $inkomenOverig;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Verslaving")
     * @ORM\Joincolumn(name="primaireproblematiek_id")
     */
    private $primaireproblematiek;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Gebruikswijze")
     * @ORM\Jointable(
     *     name="hi5_intakes_primaireproblematieksgebruikswijzen",
     *     joinColumns={@ORM\JoinColumn(name="hi5_intake_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="primaireproblematieksgebruikswijze_id")}
     * )
     */
    private $primaireproblematiekGebruikswijzen;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Frequentie")
     */
    private $primaireproblematieksfrequentie;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Periode")
     */
    private $primaireproblematieksperiode;

    /**
     * @ORM\Column(name="eerste_gebruik", type="date", nullable=true)
     */
    private $eersteGebruik;

    /**
     * @ORM\ManyToOne(targetEntity="Bedrijfitem")
     */
    private $bedrijfitem1;

    /**
     * @ORM\ManyToOne(targetEntity="Bedrijfitem")
     */
    private $bedrijfitem2;
}
