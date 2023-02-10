<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Entity\Verblijfsstatus;
use AppBundle\Entity\Inkomen;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="instanties_awbz_intakes",
 *     indexes={
 *     }
 * )
 */
class AwbzInstantieIntake
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $awbz_intake_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $instantie_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;
}
