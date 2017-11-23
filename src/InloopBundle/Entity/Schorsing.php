<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="InloopBundle\Repository\SchorsingRepository")
 * @ORM\Table(name="schorsingen")
 */
class Schorsing
{
    const DOELWIT_MEDEWERKER = 1;
    const DOELWIT_STAGIAIR = 2;
    const DOELWIT_VRIJWILLIGER = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="datum_van", type="date")
     */
    private $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date")
     */
    private $datumTot;

    /**
     * @ORM\Column(name="remark", type="text")
     */
    private $opmerking;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $bijzonderheden;

    /**
     * @ORM\Column(name="overig_reden", nullable=true)
     */
    private $redenOverig;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $gezien;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aangifte;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nazorg;

    /**
     * @ORM\Column(name="agressie", type="boolean", nullable=true)
     */
    private $agressie;

    /**
     * @ORM\Column(name="aggressie_doelwit", nullable=true)
     */
    private $doelwitAgressie1;

    /**
     * @ORM\Column(name="aggressie_doelwit2", nullable=true)
     */
    private $doelwitAgressie2;

    /**
     * @ORM\Column(name="aggressie_doelwit3", nullable=true)
     */
    private $doelwitAgressie3;

    /**
     * @ORM\Column(name="aggressie_doelwit4", nullable=true)
     */
    private $doelwitAgressie4;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie1;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker2", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie2;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker3", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie3;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker4", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie4;

    /**
     * @ORM\Column(length=100, nullable=false)
     */
    private $locatiehoofd;

    /**
     * @ORM\ManyToMany(targetEntity="Locatie")
     * @ORM\JoinTable(name="schorsing_locatie")
     */
    private $locaties;

    /**
     * ORM\ManyToMany(targetEntity="SchorsingReden")
     * ORM\JoinTable(
     *     name="schorsingen_redenen",
     *     joinColumns={@ORM\JoinColumn(name="schorsing_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="reden_id")}
     * ).
     */
    private $redenen;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $klant;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    public function getId()
    {
        return $this->id;
    }

    public function getDatumVan()
    {
        return $this->datumVan;
    }

    public function setDatumVan($datumVan)
    {
        $this->datumVan = $datumVan;

        return $this;
    }

    public function getDatumTot()
    {
        return $this->datumTot;
    }

    public function setDatumTot($datumTot)
    {
        $this->datumTot = $datumTot;

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

    public function getBijzonderheden()
    {
        return $this->bijzonderheden;
    }

    public function setBijzonderheden($bijzonderheden)
    {
        $this->bijzonderheden = $bijzonderheden;

        return $this;
    }

    public function getRedenOverig()
    {
        return $this->redenOverig;
    }

    public function setRedenOverig($redenOverig)
    {
        $this->redenOverig = $redenOverig;

        return $this;
    }

    public function getGezien()
    {
        return $this->gezien;
    }

    public function setGezien($gezien)
    {
        $this->gezien = $gezien;

        return $this;
    }

    public function getAangifte()
    {
        return $this->aangifte;
    }

    public function setAangifte($aangifte)
    {
        $this->aangifte = $aangifte;

        return $this;
    }

    public function getNazorg()
    {
        return $this->nazorg;
    }

    public function setNazorg($nazorg)
    {
        $this->nazorg = $nazorg;

        return $this;
    }

    public function getAgressie()
    {
        return $this->agressie;
    }

    public function setAgressie($agressie)
    {
        $this->agressie = $agressie;

        return $this;
    }

    public function getDoelwitAgressie1()
    {
        return $this->doelwitAgressie1;
    }

    public function setDoelwitAgressie1($doelwitAgressie1)
    {
        $this->doelwitAgressie1 = $doelwitAgressie1;

        return $this;
    }

    public function getDoelwitAgressie2()
    {
        return $this->doelwitAgressie2;
    }

    public function setDoelwitAgressie2($doelwitAgressie2)
    {
        $this->doelwitAgressie2 = $doelwitAgressie2;

        return $this;
    }

    public function getDoelwitAgressie3()
    {
        return $this->doelwitAgressie3;
    }

    public function setDoelwitAgressie3($doelwitAgressie3)
    {
        $this->doelwitAgressie3 = $doelwitAgressie3;

        return $this;
    }

    public function getDoelwitAgressie4()
    {
        return $this->doelwitAgressie4;
    }

    public function setDoelwitAgressie4($doelwitAgressie4)
    {
        $this->doelwitAgressie4 = $doelwitAgressie4;

        return $this;
    }

    public function getTypeDoelwitAgressie1()
    {
        return $this->typeDoelwitAgressie1;
    }

    public function setTypeDoelwitAgressie1($typeDoelwitAgressie1)
    {
        $this->typeDoelwitAgressie1 = $typeDoelwitAgressie1;

        return $this;
    }

    public function getTypeDoelwitAgressie2()
    {
        return $this->typeDoelwitAgressie2;
    }

    public function setTypeDoelwitAgressie2($typeDoelwitAgressie2)
    {
        $this->typeDoelwitAgressie2 = $typeDoelwitAgressie2;

        return $this;
    }

    public function getTypeDoelwitAgressie3()
    {
        return $this->typeDoelwitAgressie3;
    }

    public function setTypeDoelwitAgressie3($typeDoelwitAgressie3)
    {
        $this->typeDoelwitAgressie3 = $typeDoelwitAgressie3;

        return $this;
    }

    public function getTypeDoelwitAgressie4()
    {
        return $this->typeDoelwitAgressie4;
    }

    public function setTypeDoelwitAgressie4($typeDoelwitAgressie4)
    {
        $this->typeDoelwitAgressie4 = $typeDoelwitAgressie4;

        return $this;
    }

    public function getLocatiehoofd()
    {
        return $this->locatiehoofd;
    }

    public function setLocatiehoofd($locatiehoofd)
    {
        $this->locatiehoofd = $locatiehoofd;

        return $this;
    }

    public function getLocaties()
    {
        return $this->locaties;
    }

    public function setLocaties($locaties)
    {
        $this->locaties = $locaties;

        return $this;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant($klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    public function getRedenen()
    {
        return $this->redenen;
    }

    public function addRedenen(SchorsingReden $reden)
    {
        $this->redenen[] = $reden;

        return $this;
    }
}
