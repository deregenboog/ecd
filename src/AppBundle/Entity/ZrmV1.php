<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifeCycleCallbacks
 * @Gedmo\Loggable
 */
class ZrmV1 extends Zrm
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $inkomen;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $dagbesteding;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $huisvesting;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $gezinsrelaties;

    /**
     * @var int
     *
     * @ORM\Column(name="geestelijke_gezondheid", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $geestelijkeGezondheid;

    /**
     * @var int
     *
     * @ORM\Column(name="fysieke_gezondheid", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $fysiekeGezondheid;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $verslaving;

    /**
     * @var int
     *
     * @ORM\Column(name="adl_vaardigheden", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $adlVaardigheden;

    /**
     * @var int
     *
     * @ORM\Column(name="sociaal_netwerk", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $sociaalNetwerk;

    /**
     * @var int
     *
     * @ORM\Column(name="maatschappelijke_participatie", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $maatschappelijkeParticipatie;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $justitie;

    /**
     * Returns an array with field names as keys and labels as values.
     *
     * @return string[]
     */
    public static function getFieldsAndLabels()
    {
        return [
            'inkomen' => 'Inkomen',
            'dagbesteding' => 'Dagbesteding',
            'huisvesting' => 'Huisvesting',
            'gezinsrelaties' => 'Gezinsrelaties',
            'geestelijkeGezondheid' => 'Geestelijke gezondheid',
            'fysiekeGezondheid' => 'Fysieke gezondheid',
            'verslaving' => 'Verslaving',
            'adlVaardigheden' => 'ADL vaardigheden',
            'sociaalNetwerk' => 'Sociaal netwerk',
            'maatschappelijkeParticipatie' => 'Maatschappelijke participatie',
            'justitie' => 'Justitie',
        ];
    }

    /**
     * @return int
     */
    public function getInkomen()
    {
        return $this->inkomen;
    }

    /**
     * @param int $inkomen
     */
    public function setInkomen($inkomen)
    {
        $this->inkomen = $inkomen;

        return $this;
    }

    /**
     * @return int
     */
    public function getDagbesteding()
    {
        return $this->dagbesteding;
    }

    /**
     * @param int $dagbesteding
     */
    public function setDagbesteding($dagbesteding)
    {
        $this->dagbesteding = $dagbesteding;

        return $this;
    }

    /**
     * @return int
     */
    public function getHuisvesting()
    {
        return $this->huisvesting;
    }

    /**
     * @param int $huisvesting
     */
    public function setHuisvesting($huisvesting)
    {
        $this->huisvesting = $huisvesting;

        return $this;
    }

    /**
     * @return int
     */
    public function getGezinsrelaties()
    {
        return $this->gezinsrelaties;
    }

    /**
     * @param int $gezinsrelaties
     */
    public function setGezinsrelaties($gezinsrelaties)
    {
        $this->gezinsrelaties = $gezinsrelaties;

        return $this;
    }

    /**
     * @return int
     */
    public function getGeestelijkeGezondheid()
    {
        return $this->geestelijkeGezondheid;
    }

    /**
     * @param int $geestelijkeGezondheid
     */
    public function setGeestelijkeGezondheid($geestelijkeGezondheid)
    {
        $this->geestelijkeGezondheid = $geestelijkeGezondheid;

        return $this;
    }

    /**
     * @return int
     */
    public function getFysiekeGezondheid()
    {
        return $this->fysiekeGezondheid;
    }

    /**
     * @param int $fysiekeGezondheid
     */
    public function setFysiekeGezondheid($fysiekeGezondheid)
    {
        $this->fysiekeGezondheid = $fysiekeGezondheid;

        return $this;
    }

    /**
     * @return int
     */
    public function getVerslaving()
    {
        return $this->verslaving;
    }

    /**
     * @param int $verslaving
     */
    public function setVerslaving($verslaving)
    {
        $this->verslaving = $verslaving;

        return $this;
    }

    /**
     * @return int
     */
    public function getAdlVaardigheden()
    {
        return $this->adlVaardigheden;
    }

    /**
     * @param int $adlVaardigheden
     */
    public function setAdlVaardigheden($adlVaardigheden)
    {
        $this->adlVaardigheden = $adlVaardigheden;

        return $this;
    }

    /**
     * @return int
     */
    public function getSociaalNetwerk()
    {
        return $this->sociaalNetwerk;
    }

    /**
     * @param int $sociaalNetwerk
     */
    public function setSociaalNetwerk($sociaalNetwerk)
    {
        $this->sociaalNetwerk = $sociaalNetwerk;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaatschappelijkeParticipatie()
    {
        return $this->maatschappelijkeParticipatie;
    }

    /**
     * @param int $maatschappelijkeParticipatie
     */
    public function setMaatschappelijkeParticipatie($maatschappelijkeParticipatie)
    {
        $this->maatschappelijkeParticipatie = $maatschappelijkeParticipatie;

        return $this;
    }

    /**
     * @return int
     */
    public function getJustitie()
    {
        return $this->justitie;
    }

    /**
     * @param int $justitie
     */
    public function setJustitie($justitie)
    {
        $this->justitie = $justitie;

        return $this;
    }
}
