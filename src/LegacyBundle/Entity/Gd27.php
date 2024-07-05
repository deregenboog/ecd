<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gd27.
 *
 * @ORM\Table(name="gd27")
 *
 * @ORM\Entity
 */
class Gd27
{
    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=50, nullable=true)
     */
    private $naam;

    /**
     * @var string
     *
     * @ORM\Column(name="voornaam", type="string", length=50, nullable=true)
     */
    private $voornaam;

    /**
     * @var string
     *
     * @ORM\Column(name="achternaam", type="string", length=50, nullable=true)
     */
    private $achternaam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="geboortedatum", type="datetime", nullable=true)
     */
    private $geboortedatum;

    /**
     * @var int
     *
     * @ORM\Column(name="klant_id", type="integer", nullable=true)
     */
    private $klantId;

    /**
     * @var string
     *
     * @ORM\Column(name="db_voornaam", type="string", length=50, nullable=true)
     */
    private $dbVoornaam;

    /**
     * @var string
     *
     * @ORM\Column(name="db_achternaam", type="string", length=50, nullable=true)
     */
    private $dbAchternaam;

    /**
     * @var string
     *
     * @ORM\Column(name="roepnaam", type="string", length=50, nullable=true)
     */
    private $roepnaam;

    /**
     * @var string
     *
     * @ORM\Column(name="land", type="string", length=255, nullable=true)
     */
    private $land;

    /**
     * @var string
     *
     * @ORM\Column(name="nationaliteit", type="string", length=255, nullable=true)
     */
    private $nationaliteit;

    /**
     * @var string
     *
     * @ORM\Column(name="woonsituatie", type="string", length=255, nullable=true)
     */
    private $woonsituatie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inschrijving", type="date", nullable=true)
     */
    private $inschrijving;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=true)
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="idd", type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     */
    private $idd;
}
