<?php

namespace IzBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\ProjectRepository")
 *
 * @ORM\Table(name="iz_projecten")
 *
 * @Gedmo\Loggable
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Project
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;

    public const STRATEGY_PRESTATIE_TOTAL = 'total';
    public const STRATEGY_PRESTATIE_STARTED = 'started';

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @ORM\Column(name="heeft_koppelingen", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $heeftKoppelingen = true;

    /**
     * @ORM\Column(name="prestatie_strategy")
     *
     * @Gedmo\Versioned
     */
    private $prestatieStrategy = self::STRATEGY_PRESTATIE_TOTAL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kleur;

    public function getHeeftKoppelingen()
    {
        return $this->heeftKoppelingen;
    }

    public function setHeeftKoppelingen($heeftKoppelingen)
    {
        $this->heeftKoppelingen = $heeftKoppelingen;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(?\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getPrestatieStrategy()
    {
        return $this->prestatieStrategy;
    }

    public function setPrestatieStrategy($prestatieStrategy)
    {
        $this->prestatieStrategy = $prestatieStrategy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKleur():?string
    {
        return $this->kleur;
    }

    /**
     * @param mixed $kleur
     */
    public function setKleur(?string $kleur): void
    {
        $this->kleur = $kleur;
    }


}
