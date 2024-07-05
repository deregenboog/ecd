<?php

namespace HsBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="hs_betalingen")
 *
 * @Gedmo\Loggable
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Betaling
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $referentie;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $info;

    /**
     * @ORM\Column(type="decimal", scale=2)
     *
     * @Gedmo\Versioned
     */
    private $bedrag;

    /**
     * @var Factuur
     *
     * @ORM\ManyToOne(targetEntity="Factuur", inversedBy="betalingen")
     *
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Gedmo\Versioned
     */
    private $factuur;

    public function __construct()
    {
        $this->datum = new \DateTime('today');
    }

    public function __toString()
    {
        // check if locale set in %framework.default_locale% is supported
        $locale = setlocale(LC_ALL, 0);
        if ($locale && 'C' !== $locale) {
            return trim(money_format('%(#1n', (float) $this->bedrag));
        }

        // or fallback
        return '€ '.number_format((float) $this->bedrag, 2, ',', '.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReferentie()
    {
        return $this->referentie;
    }

    public function setReferentie($referentie)
    {
        $this->referentie = $referentie;

        return $this;
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

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }

    public function setBedrag($bedrag)
    {
        $this->bedrag = $bedrag;

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
