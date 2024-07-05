<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="awbz_indicaties",
 *     indexes={
 *
 *         @ORM\Index(name="idx_awbz_indicaties_klant_id", columns={"klant_id"})
 *     }
 * )
 */
class AwbzIndicatie
{
    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $klant_id;

    /**
     * @ORM\Column(type="date")
     */
    private $begindatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $einddatum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $begeleiding_per_week;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $activering_per_week;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hoofdaannemer_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $aangevraagd_id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $aangevraagd_datum;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $aangevraagd_niet;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;
}
