<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="registraties_2011", indexes={
 *
 *     @ORM\Index(name="idx_registraties_klant_id_locatie_id", columns={"klant_id", "locatie_id"}),
 *     @ORM\Index(name="idx_registraties_locatie_id_closed", columns={"locatie_id", "closed"}),
 *     @ORM\Index(name="IDX_FB4123F43C427B2F", columns={"klant_id"}),
 *     @ORM\Index(name="IDX_FB4123F44947630C", columns={"locatie_id"}),
 *     @ORM\Index(name="IDX_FB4123F44947630C4C74DE4C5E4E404", columns={"locatie_id", "closed", "binnen_date"})
 * })
 *
 * @ORM\HasLifecycleCallbacks
 */
class Registratie2011
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
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
    private $klant_id_before_constraint;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $locatie_id_before_constraint;

    /**
     * @ORM\Column(type="datetime")
     */
    private $binnen;

    /**
     * @ORM\Column(name="binnen_date", type="date", nullable=true)
     */
    private $binnenDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $buiten;

    /**
     * @ORM\Column(type="integer")
     */
    private $douche;

    /**
     * @ORM\Column(type="integer")
     */
    private $mw;

    /**
     * @ORM\Column(type="integer")
     */
    private $gbrv;

    /**
     * @ORM\Column(type="boolean")
     */
    private $kleding;

    /**
     * @ORM\Column(type="boolean")
     */
    private $maaltijd;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activering;

    /**
     * @ORM\Column(type="boolean")
     */
    private $veegploeg;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $closed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;
}
