<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Legitimatie;
use AppBundle\Entity\Locatie;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $klant_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $medewerker_id;

    /**
     * @ORM\Column(name="datum_intake", type="date", nullable=true)
     */
    private $datumIntake;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $verblijfstatus_id;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $legitimatie_id;

    /**
     * @ORM\Column(name="legitimatie_nummer", type="string", length=255, nullable=true)
     */
    private $legitimatieNummer;

    /**
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
     * @ORM\Column(name="verslaving_overig", type="string", length=255, nullable=true)
     */
    private $verslavingOverig;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $woonsituatie_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie1_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie2_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie3_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $werklocatie_id;

    /**
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
     * @ORM\Column(name="inkomen_overig", type="string", length=255, nullable=true)
     */
    private $inkomenOverig;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $primaireproblematiek_id;

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
    private $bedrijfitem_1_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bedrijfitem_2_id;
}
