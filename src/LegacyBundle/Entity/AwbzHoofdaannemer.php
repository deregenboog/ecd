<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="awbz_hoofdaannemers",
 *     indexes={
 *
 *         @ORM\Index(name="idx_awbz_hoofdaannemers_klant_id", columns={"klant_id"}),
 *         @ORM\Index(name="idx_awbz_hoofdaannemers_hoofdaannemer_id", columns={"hoofdaannemer_id"})
 *     }
 * )
 */
class AwbzHoofdaannemer
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
    private $hoofdaannemer_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;
}
