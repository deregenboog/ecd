<?php

namespace LegacyBundle\Entity;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="hi5_evaluaties",
 *     indexes={
 *
 *         @ORM\Index(name="idx_hi5_evaluaties_klant_id", columns={"klant_id"})
 *     }
 * )
 *
 * @ORM\Entity
 */
class Hi5Evaluatie
{
    /**
     * @ORM\Column(name="id", type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $klant_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $medewerker_id;

    /**
     * @ORM\Column(name="datumevaluatie", type="date")
     */
    private $datumevaluatie;

    /**
     * @ORM\Column(name="werkproject", type="string", length=255)
     */
    private $werkproject;

    /**
     * @ORM\Column(name="aantal_dagdelen", type="integer")
     */
    private $aantalDagdelen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdatumtraject", type="date")
     */
    private $startdatumtraject;

    /**
     * @ORM\Column(name="datum_intake", type="date")
     */
    private $datumIntake;

    /**
     * @ORM\Column(name="verslagvan", type="date")
     */
    private $verslagvan;

    /**
     * @ORM\Column(name="verslagtm", type="date")
     */
    private $verslagtm;

    /**
     * @ORM\Column(name="extraanwezigen", type="string", length=255)
     */
    private $extraanwezigen;

    /**
     * @ORM\Column(name="opmerkingen_overige", type="text", length=65535)
     */
    private $opmerkingenOverige;

    /**
     * @var string
     *
     * @ORM\Column(name="afspraken_afgelopen", type="text", length=65535)
     */
    private $afsprakenAfgelopen;

    /**
     * @ORM\Column(name="watdoejij", type="text", length=65535)
     */
    private $watdoejij;

    /**
     * @ORM\Column(name="watdoetb", type="text", length=65535)
     */
    private $watdoetb;

    /**
     * @ORM\Column(name="watdoewb", type="text", length=65535)
     */
    private $watdoewb;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;
}
