<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_matching_vrijwilligers")
 * @Gedmo\Loggable
 */
class MatchingVrijwilliger
{
    use IdentifiableTrait;

    /**
     * @var IzVrijwilliger
     *
     * @ORM\OneToOne(targetEntity="IzVrijwilliger", inversedBy="matching"))
     * @ORM\JoinColumn(name="iz_vrijwilliger_id")
     */
    private $izVrijwilliger;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $info;

    /**
     * @var Collection|Doelgroep[]
     *
     * @ORM\ManyToMany(targetEntity="Doelgroep")
     */
    private $doelgroepen;

    /**
     * @var Collection|Hulpvraagsoort[]
     *
     * @ORM\ManyToMany(targetEntity="Hulpvraagsoort")
     */
    private $hulpvraagsoorten;

    /**
     * @var bool
     *
     * @ORM\Column(name="voorkeur_voor_nederlands", type="boolean")
     */
    private $voorkeurVoorNederlands;

    public function __construct(IzVrijwilliger $izVrijwilliger)
    {
        $this->izVrijwilliger = $izVrijwilliger;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function getDoelgroepen()
    {
        return $this->doelgroepen;
    }

    public function getHulpvraagsoorten()
    {
        return $this->hulpvraagsoorten;
    }

    public function isVoorkeurVoorNederlands()
    {
        return $this->voorkeurVoorNederlands;
    }

    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    public function setDoelgroepen($doelgroepen)
    {
        $this->doelgroepen = $doelgroepen;

        return $this;
    }

    public function setHulpvraagsoorten($hulpvraagsoorten)
    {
        $this->hulpvraagsoorten = $hulpvraagsoorten;

        return $this;
    }

    public function setVoorkeurVoorNederlands($voorkeurVoorNederlands)
    {
        $this->voorkeurVoorNederlands = (bool) $voorkeurVoorNederlands;

        return $this;
    }
}
