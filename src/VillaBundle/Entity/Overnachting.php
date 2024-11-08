<?php

namespace VillaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="villa_overnachtingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 *
 * Registreert de overnachtingen van een slaper.
 */
class Overnachting
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var Slaper
     *
     * @ORM\ManyToOne(targetEntity="VillaBundle\Entity\Slaper", inversedBy="overnachtingen", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $slaper;

    /** @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $opmerking = "";


    /**
     * @var \DateTime
     * @ORM\Column(type="date",nullable=true)
     */
    protected $datum;


    /**
     * @return \DateTime
     */
    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }

    /**
     * @param \DateTime $datum
     */
    public function setDatum(\DateTime $datum): void
    {
        $this->datum = $datum;
    }

    /**
     * @return Slaper
     */
    public function getSlaper(): Slaper
    {
        return $this->slaper;
    }

    /**
     * @param Slaper $slaper
     */
    public function setSlaper(Slaper $slaper): void
    {
        $this->slaper = $slaper;
    }

    /**
     * @return string|null
     */
    public function getOpmerking(): string
    {
        return mb_convert_encoding($this->opmerking ?? "", 'ISO-8859-1','UTF-8');
    }

    /**
     * @param string|null $opmerking
     */
    public function setOpmerking(?string $opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');
        return $this;
    }


}
