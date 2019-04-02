<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Klant;

/**
 * Hi5Evaluaties.
 *
 * @ORM\Table(name="hi5_evaluaties", indexes={@ORM\Index(name="idx_hi5_evaluaties_klant_id", columns={"klant_id"})})
 * @ORM\Entity
 */
class Hi5Evaluatie
{
    /**
     * @ORM\Column(name="id", type="integer")
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
     * @ORM\Column(name="datumevaluatie", type="date", nullable=false)
     */
    private $datumevaluatie;

    /**
     * @ORM\Column(name="werkproject", type="string", length=255, nullable=false)
     */
    private $werkproject;

    /**
     * @ORM\Column(name="aantal_dagdelen", type="integer", nullable=false)
     */
    private $aantalDagdelen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdatumtraject", type="date", nullable=false)
     */
    private $startdatumtraject;

    /**
     * @ORM\Column(name="datum_intake", type="date", nullable=false)
     */
    private $datumIntake;

    /**
     * @ORM\Column(name="verslagvan", type="date", nullable=false)
     */
    private $verslagvan;

    /**
     * @ORM\Column(name="verslagtm", type="date", nullable=false)
     */
    private $verslagtm;

    /**
     * @ORM\Column(name="extraanwezigen", type="string", length=255, nullable=false)
     */
    private $extraanwezigen;

    /**
     * @ORM\Column(name="opmerkingen_overige", type="text", length=65535, nullable=false)
     */
    private $opmerkingenOverige;

    /**
     * @var string
     *
     * @ORM\Column(name="afspraken_afgelopen", type="text", length=65535, nullable=false)
     */
    private $afsprakenAfgelopen;

    /**
     * @ORM\Column(name="watdoejij", type="text", length=65535, nullable=false)
     */
    private $watdoejij;

    /**
     * @ORM\Column(name="watdoetb", type="text", length=65535, nullable=false)
     */
    private $watdoetb;

    /**
     * @ORM\Column(name="watdoewb", type="text", length=65535, nullable=false)
     */
    private $watdoewb;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;
}
