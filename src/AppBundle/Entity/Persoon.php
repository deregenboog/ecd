<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class Persoon
{
    use NameTrait, AddressTrait, TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    protected $geboortedatum;

    /**
     * @ORM\Column(name="BSN", type="string", nullable=true)
     */
    protected $bsn;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $medewerker;

    /**
     * @var Geslacht
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $geslacht;

    /**
     * @var Land
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Land")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $land;

    /**
     * @var Nationaliteit
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Nationaliteit")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $nationaliteit;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $werkgebied;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $postcodegebied;

    /**
     * @ORM\Column(name="geen_post", type="boolean")
     */
    protected $geenPost = false;

    /**
     * @ORM\Column(name="geen_email", type="boolean")
     */
    protected $geenEmail = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $opmerking;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $disabled = false;

    public function getId()
    {
        return $this->id;
    }

    public function getGeslacht()
    {
        return $this->geslacht;
    }

    public function setGeslacht(Geslacht $geslacht)
    {
        $this->geslacht = $geslacht;

        return $this;
    }

    public function getGeboortedatum()
    {
        return $this->geboortedatum;
    }

    public function setGeboortedatum(\DateTime $geboortedatum = null)
    {
        $this->geboortedatum = $geboortedatum;

        return $this;
    }

    public function getPostcodegebied()
    {
        return $this->postcodegebied;
    }

    public function setPostcodegebied($postcodegebied)
    {
        $this->postcodegebied = $postcodegebied;

        return $this;
    }

    public function isGeenPost()
    {
        return $this->geenPost;
    }

    public function isGeenEmail()
    {
        return $this->geenEmail;
    }

    public function getWerkgebied()
    {
        return $this->werkgebied;
    }

    public function setWerkgebied($werkgebied)
    {
        $this->werkgebied = $werkgebied;

        return $this;
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

    public function setLand(Land $land)
    {
        $this->land = $land;

        return $this;
    }

    public function getBsn()
    {
        return $this->bsn;
    }

    public function setBsn($bsn)
    {
        $this->bsn = $bsn;

        return $this;
    }

    public function getNationaliteit()
    {
        return $this->nationaliteit;
    }

    public function setNationaliteit(Nationaliteit $nationaliteit)
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
}
