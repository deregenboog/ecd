<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Entity\Verblijfsstatus;
use AppBundle\Entity\Inkomen;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="awbz_intakes",
 *     indexes={}
 * )
 */
class AwbzIntake
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datum_intake;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Verblijfsstatus")
     */
    private $verblijfstatus;

    /**
     * @ORM\Column(nullable=true)
     */
    private $postadres;

    /**
     * @ORM\Column(length=6, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(nullable=true)
     */
    private $woonplaats;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_NL_sinds", type="date", nullable=true)
     */
    private $verblijfInNederlandSinds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_amsterdam_sinds", type="date", nullable=true)
     */
    private $verblijfInAmsterdamSinds;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Legitimatie")
     */
    private $legitimatie;

    /**
     * @var string
     *
     * @ORM\Column(name="legitimatie_nummer", nullable=true)
     */
    private $legitimatieNummer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="legitimatie_geldig_tot", type="date", nullable=true)
     */
    private $legitimatieGeldigTot;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Frequentie")
     * @ORM\JoinColumn(name="verslavingsfrequentie_id", nullable=true)
     */
    private $frequentie;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Periode")
     * @ORM\JoinColumn(name="verslavingsperiode_id", nullable=true)
     */
    private $periode;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Woonsituatie")
     */
    private $woonsituatie;

    /**
     * @ORM\Column(type="text", name="verwachting_dienstaanbod", nullable=true)
     */
    private $verwachtingDienstaanbod;

    /**
     * @ORM\Column(type="text", name="toekomstplannen", nullable=true)
     */
    private $toekomstplannen;

    /**
     * @ORM\Column(type="text", name="opmerking_andere_instanties", nullable=true)
     */
    private $opmerkingAndereInstanties;

    /**
     * @ORM\Column(type="text", name="medische_achtergrond", nullable=true)
     */
    private $medischeAchtergrond;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     * @ORM\JoinColumn(name="locatie1_id", nullable=true)
     */
    private $gebruikersruimte;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     * @ORM\JoinColumn(name="locatie2_id", nullable=true)
     */
    private $intakelocatie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $indruk;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $doelgroep;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @ORM\Column(name="verslaving_overig", nullable=true)
     */
    private $verslavingOverig;

    /**
     * @ORM\Column(name="inkomen_overig", nullable=true)
     */
    private $inkomenOverig;

    /**
     * @ORM\Column(type="boolean", name="informele_zorg", nullable=true, options={"default" : 0})
     */
    private $informeleZorg = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $dagbesteding = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $inloophuis = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $hulpverlening = false;

    /**
     * @ORM\Column(name="mag_gebruiken", type="boolean", nullable=true)
     */
    private $magGebruiken;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Verslaving")
     */
    private $primaireProblematiek;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Frequentie")
     * @ORM\JoinColumn(name="primaireproblematieksfrequentie_id")
     */
    private $primaireProblematiekFrequentie;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Periode")
     * @ORM\JoinColumn(name="primaireproblematieksperiode_id")
     */
    private $primaireProblematiekPeriode;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Gebruikswijze")
     * @ORM\JoinTable(
     *     name="awbz_intakes_primaireproblematieksgebruikswijzen",
     *     inverseJoinColumns=@ORM\JoinColumn(name="primaireproblematieksgebruikswijze_id")
     * )
     */
    private $primaireProblematiekGebruikswijzen;

    /**
     * @ORM\Column(name="eerste_gebruik", type="date", nullable=true)
     */
    private $eersteGebruik;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     * @ORM\JoinColumn(name="locatie3_id")
     */
    private $locatie3;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Infobaliedoelgroep")
     */
    private $infobaliedoelgroep;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Inkomen")
     * @ORM\JoinTable(name="inkomens_awbz_intakes")
     */
    private $inkomens;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Verslaving")
     * @ORM\JoinTable(name="awbz_intakes_verslavingen")
     */
    private $verslavingen;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Gebruikswijze")
     * @ORM\JoinTable(name="awbz_intakes_verslavingsgebruikswijzen")
     */
    private $gebruikswijzen;

    /**
     * @ORM\ManyToMany(targetEntity="InloopBundle\Entity\Instantie")
     * @ORM\JoinTable(name="instanties_awbz_intakes")
     */
    private $instanties;
}
