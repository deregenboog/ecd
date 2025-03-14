<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="hs_herinneringen")
 *
 * @Gedmo\Loggable
 */
class Herinnering
{
    public const TYPE_HERINNERING = 'Herinnering';
    public const TYPE_AANMANING = 'Aanmaning';
    public const TYPE_INGEBREKESTELLING = 'Ingebrekestelling';

    public const TYPES = [
        'Herinnering' => self::TYPE_HERINNERING,
        'Aanmaning' => self::TYPE_AANMANING,
        'Ingebrekestelling' => self::TYPE_INGEBREKESTELLING,
    ];

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column
     *
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var Factuur
     *
     * @ORM\ManyToOne(targetEntity="Factuur", inversedBy="herinneringen")
     *
     * @Gedmo\Versioned
     */
    private $factuur;

    public function __construct()
    {
        $this->setDatum(new \DateTime());
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->type, $this->datum->format('d-m-Y'));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if (!in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException();
        }

        $this->type = $type;

        return $this;
    }

    public function getFactuur()
    {
        return $this->factuur;
    }

    public function setFactuur(Factuur $factuur)
    {
        $this->factuur = $factuur;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
