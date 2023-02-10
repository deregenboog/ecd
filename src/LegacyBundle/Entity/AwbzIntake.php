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
 *     indexes={
 *         @ORM\Index(name="idx_awbz_intakes_klant_id", columns={"klant_id"})
 *     }
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $klant_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $medewerker_id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datum_intake;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $verblijfstatus_id;

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
     * @ORM\Column(type="date", nullable=true)
     */
    private $verblijf_in_NL_sinds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $verblijf_in_amsterdam_sinds;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $legitimatie_id;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $verslavingsfrequentie_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $verslavingsperiode_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $woonsituatie_id;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie1_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie2_id;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $primaireProblematiek_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $primaireproblematieksfrequentie_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $primaireproblematieksperiode_id;

    /**
     * @ORM\Column(name="eerste_gebruik", type="date", nullable=true)
     */
    private $eersteGebruik;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie3_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $infobaliedoelgroep_id;
}
