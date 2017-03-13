<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huurovereenkomsten")
 * @ORM\HasLifecycleCallbacks
 */
class Huurovereenkomst
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $opzegdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $einddatum;

    /**
     * @var Huuraanbod
     * @ORM\ManyToOne(targetEntity="Huuraanbod")
     */
    private $huuraanbod;

    /**
     * @var Huurverzoek
     * @ORM\ManyToOne(targetEntity="Huurverzoek")
     */
    private $huurverzoek;

    /**
     * @var HuurovereenkomstAfsluiting
     * @ORM\ManyToOne(targetEntity="HuurovereenkomstAfsluiting")
     */
    private $huurovereenkomstAfsluiting;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="odp_huurovereenkomst_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="odp_huurovereenkomst_document")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $documenten;

    public function __construct()
    {
        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
    }

    public function __toString()
    {
        return implode(' - ', [$this->huurverzoek->getHuurder(), $this->huuraanbod->getVerhuurder()]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getHuuraanbod()
    {
        return $this->huuraanbod;
    }

    public function getHuurverzoek()
    {
        return $this->huurverzoek;
    }

    public function setHuuraanbod(Huuraanbod $huuraanbod)
    {
        $this->huuraanbod = $huuraanbod;

        return $this;
    }

    public function setHuurverzoek(Huurverzoek $huurverzoek)
    {
        $this->huurverzoek = $huurverzoek;

        return $this;
    }

    public function getHuurder()
    {
        return $this->huurverzoek->getHuurder();
    }

    public function getVerhuurder()
    {
        return $this->huuraanbod->getVerhuurder();
    }

    public function isDeletable()
    {
        return false;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;

        return $this;
    }

    public function getDocumenten()
    {
        return $this->documenten;
    }

    public function addDocument(Document $document)
    {
        $this->documenten[] = $document;

        return $this;
    }

    public function getOpzegdatum()
    {
        return $this->opzegdatum;
    }

    public function setOpzegdatum($opzegdatum)
    {
        $this->opzegdatum = $opzegdatum;

        return $this;
    }
}
