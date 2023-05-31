<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Util\DateTimeUtil;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="locatie_tijden")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Locatietijd
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locatie", inversedBy="locatietijden")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $locatie;

    /**
     * @ORM\Column(name="dag_van_de_week", type="integer")
     * @Gedmo\Versioned
     */
    private $dagVanDeWeek;

    /**
     * @ORM\Column(name="openingstijd", type="time")
     * @Gedmo\Versioned
     */
    private $openingstijd;

    /**
     * @ORM\Column(name="sluitingstijd", type="time")
     * @Gedmo\Versioned
     */
    private $sluitingstijd;

    public function __toString()
    {
        return sprintf(
            '%s %s-%s (%s)',
            DateTimeUtil::dayOfWeek($this->getDagVanDeWeek()),
            $this->openingstijd->format('H:i'),
            $this->sluitingstijd->format('H:i'),
            $this->locatie
        );
    }

    /**
     * @return Locatie
     */
    public function getLocatie()
    {
        return $this->locatie;
    }

    /**
     * @param Locatie $locatie
     */
    public function setLocatie(Locatie $locatie)
    {
        $this->locatie = $locatie;

        return $this;
    }

    /**
     * @return int
     */
    public function getDagVanDeWeek()
    {
        return $this->dagVanDeWeek;
    }

    /**
     * @param int $dagVanDeWeek
     */
    public function setDagVanDeWeek($dagVanDeWeek)
    {
        $this->dagVanDeWeek = $dagVanDeWeek;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOpeningstijd()
    {
        return $this->openingstijd;
    }

    /**
     * @param \DateTime $openingstijd
     */
    public function setOpeningstijd(\DateTime $openingstijd)
    {
        $this->openingstijd = $openingstijd;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSluitingstijd()
    {
        return $this->sluitingstijd;
    }

    /**
     * @param \DateTime $sluitingstijd
     */
    public function setSluitingstijd(\DateTime $sluitingstijd)
    {
        $this->sluitingstijd = $sluitingstijd;

        return $this;
    }
}
