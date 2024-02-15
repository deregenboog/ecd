<?php

namespace LegacyBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table("zrm_v2_reports")
 */
class ZrmV2Report
{
    use IdentifiableTrait;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $financien;

    /**
     * @ORM\Column(name="werk_opleiding", type="integer", nullable=true)
     */
    private $werkOpleiding;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tijdsbesteding;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $huisvesting;

    /**
     * @ORM\Column(name="huiselijke_relaties", type="integer", nullable=true)
     */
    private $huiselijkeRelaties;

    /**
     * @ORM\Column(name="geestelijke_gezondheid", type="integer", nullable=true)
     */
    private $geestelijkeGezondheid;

    /**
     * @ORM\Column(name="lichamelijke_gezondheid", type="integer", nullable=true)
     */
    private $lichamelijkeGezondheid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $middelengebruik;

    /**
     * @ORM\Column(name="basale_adl", type="integer", nullable=true)
     */
    private $basaleAdl;

    /**
     * @ORM\Column(name="instrumentele_adl", type="integer", nullable=true)
     */
    private $instrumenteleAdl;

    /**
     * @ORM\Column(name="sociaal_netwerk", type="integer", nullable=true)
     */
    private $sociaalNetwerk;

    /**
     * @ORM\Column(name="maatschappelijke_participatie", type="integer", nullable=true)
     */
    private $maatschappelijkeParticipatie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $justitie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $modified;

    /**
     * @deprecated
     * @ORM\Column(type="integer")
     */
    private $klant_id;

    /**
     * @deprecated
     * @ORM\Column
     */
    private $model;

    /**
     * @deprecated
     * @ORM\Column(type="integer")
     */
    private $foreign_key;

    /**
     * @deprecated
     * @ORM\Column
     */
    private $request_module;

    /**
     * Returns an array with field names as keys and labels as values.
     *
     * @return string[]
     */
    public static function getFieldsAndLabels()
    {
        return [
            'financien' => 'Financiën',
            'werkOpleiding' => 'Werk en opleiding',
            'tijdsbesteding' => 'Tijdsbesteding',
            'huisvesting' => 'Huisvesting',
            'huiselijkeRelaties' => 'Huiselijke relaties',
            'geestelijkeGezondheid' => 'Geestelijke gezondheid',
            'lichamelijkeGezondheid' => 'Lichamelijke gezondheid',
            'middelengebruik' => 'Middelengebruik',
            'basaleAdl' => 'Basale ADL',
            'instrumenteleAdl' => 'Instrumentele ADL',
            'sociaalNetwerk' => 'Sociaal netwerk',
            'maatschappelijkeParticipatie' => 'Maatschappelijke participatie',
            'justitie' => 'Justitie',
        ];
    }

    /**
     * @return int
     */
    public function getFinancien()
    {
        return $this->financien;
    }

    /**
     * @param int $financien
     */
    public function setFinancien($financien)
    {
        $this->financien = $financien;

        return $this;
    }

    /**
     * @return int
     */
    public function getWerkOpleiding()
    {
        return $this->werkOpleiding;
    }

    /**
     * @param int $werkOpleiding
     */
    public function setWerkOpleiding($werkOpleiding)
    {
        $this->werkOpleiding = $werkOpleiding;

        return $this;
    }

    /**
     * @return int
     */
    public function getTijdsbesteding()
    {
        return $this->tijdsbesteding;
    }

    /**
     * @param int $tijdsbesteding
     */
    public function setTijdsbesteding($tijdsbesteding)
    {
        $this->tijdsbesteding = $tijdsbesteding;

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
    public function getHuiselijkeRelaties()
    {
        return $this->huiselijkeRelaties;
    }

    /**
     * @param int $huiselijkeRelaties
     */
    public function setHuiselijkeRelaties($huiselijkeRelaties)
    {
        $this->huiselijkeRelaties = $huiselijkeRelaties;

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
    public function getLichamelijkeGezondheid()
    {
        return $this->lichamelijkeGezondheid;
    }

    /**
     * @param int $lichamelijkeGezondheid
     */
    public function setLichamelijkeGezondheid($lichamelijkeGezondheid)
    {
        $this->lichamelijkeGezondheid = $lichamelijkeGezondheid;

        return $this;
    }

    /**
     * @return int
     */
    public function getMiddelengebruik()
    {
        return $this->middelengebruik;
    }

    /**
     * @param int $middelengebruik
     */
    public function setMiddelengebruik($middelengebruik)
    {
        $this->middelengebruik = $middelengebruik;

        return $this;
    }

    /**
     * @return int
     */
    public function getBasaleAdl()
    {
        return $this->basaleAdl;
    }

    /**
     * @param int $basaleAdl
     */
    public function setBasaleAdl($basaleAdl)
    {
        $this->basaleAdl = $basaleAdl;

        return $this;
    }

    /**
     * @return int
     */
    public function getInstrumenteleAdl()
    {
        return $this->instrumenteleAdl;
    }

    /**
     * @param int $instrumenteleAdl
     */
    public function setInstrumenteleAdl($instrumenteleAdl)
    {
        $this->instrumenteleAdl = $instrumenteleAdl;

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
