<?php

namespace AppBundle\Entity;

use AppBundle\Model\AddressTrait;
use AppBundle\Model\PersonTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Persoon
{
    use PersonTrait, AddressTrait, TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var Land
     * @ORM\ManyToOne(targetEntity="Land")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $land;

    /**
     * @var Nationaliteit
     * @ORM\ManyToOne(targetEntity="Nationaliteit")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $nationaliteit;

    /**
     * @ORM\Column(name="geen_post", type="boolean")
     * @Gedmo\Versioned
     */
    protected $geenPost = false;

    /**
     * @ORM\Column(name="geen_email", type="boolean")
     * @Gedmo\Versioned
     */
    protected $geenEmail = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $opmerking;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    protected $disabled = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    protected $roker = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    protected $dierenbezitter = false;

    public function getId()
    {
        return $this->id;
    }

    public function isGeenPost()
    {
        return $this->geenPost;
    }

    public function isGeenEmail()
    {
        return $this->geenEmail;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getLand()
    {
        return $this->land;
    }

    public function setLand(Land $land = null)
    {
        $this->land = $land;

        return $this;
    }

    public function getNationaliteit()
    {
        return $this->nationaliteit;
    }

    public function setNationaliteit(Nationaliteit $nationaliteit = null)
    {
        $this->nationaliteit = $nationaliteit;

        return $this;
    }

    public function getGeenPost()
    {
        return $this->geenPost;
    }

    public function setGeenPost($geenPost)
    {
        $this->geenPost = $geenPost;

        return $this;
    }

    public function getGeenEmail()
    {
        return $this->geenEmail;
    }

    public function setGeenEmail($geenEmail)
    {
        $this->geenEmail = $geenEmail;

        return $this;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function isRoker()
    {
        return $this->roker;
    }

    public function setRoker($roker)
    {
        $this->roker = (bool) $roker;

        return $this;
    }

    public function isDierenbezitter()
    {
        return $this->dierenbezitter;
    }

    public function setDierenbezitter($dierenbezitter)
    {
        $this->dierenbezitter = (bool) $dierenbezitter;

        return $this;
    }
}
