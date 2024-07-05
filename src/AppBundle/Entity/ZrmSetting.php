<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="zrm_settings")
 *
 * @Gedmo\Loggable
 */
class ZrmSetting
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(name="request_module", type="string", length=50)
     *
     * @Gedmo\Versioned
     */
    private $requestModule;

    public function setRequestModule($requestModule)
    {
        $this->requestModule = $requestModule;

        return $this;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $inkomen;

    public function setInkomen($inkomen)
    {
        $this->inkomen = $inkomen;

        return $this;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $dagbesteding;

    public function setDagbesteding($dagbesteding)
    {
        $this->dagbesteding = $dagbesteding;

        return $this;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $huisvesting;

    public function setHuisvesting($huisvesting)
    {
        $this->huisvesting = $huisvesting;

        return $this;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $gezinsrelaties;

    public function setGezinsrelaties($gezinsrelaties)
    {
        $this->gezinsrelaties = $gezinsrelaties;

        return $this;
    }

    /**
     * @ORM\Column(name="geestelijke_gezondheid", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $geestelijkeGezondheid;

    public function setGeestelijkeGezondheid($geestelijkeGezondheid)
    {
        $this->geestelijkeGezondheid = $geestelijkeGezondheid;

        return $this;
    }

    /**
     * @ORM\Column(name="fysieke_gezondheid", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $fysiekeGezondheid;

    public function setFysiekeGezondheid($fysiekeGezondheid)
    {
        $this->fysiekeGezondheid = $fysiekeGezondheid;

        return $this;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $verslaving;

    public function setVerslaving($verslaving)
    {
        $this->verslaving = $verslaving;

        return $this;
    }

    /**
     * @ORM\Column(name="adl_vaardigheden", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $adlVaardigheden;

    public function setAdlVaardigheden($adlVaardigheden)
    {
        $this->adlVaardigheden = $adlVaardigheden;

        return $this;
    }

    /**
     * @ORM\Column(name="sociaal_netwerk", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $sociaalNetwerk;

    public function setSociaalNetwerk($sociaalNetwerk)
    {
        $this->sociaalNetwerk = $sociaalNetwerk;

        return $this;
    }

    /**
     * @ORM\Column(name="maatschappelijke_participatie", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $maatschappelijkeParticipatie;

    public function setMaatschappelijkeParticipatie($maatschappelijkeParticipatie)
    {
        $this->maatschappelijkeParticipatie = $maatschappelijkeParticipatie;

        return $this;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $justitie;

    public function setjustitie($justitie)
    {
        $this->justitie = $justitie;

        return $this;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;
}
