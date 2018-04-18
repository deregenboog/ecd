<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="klanten",
 *     indexes={
 *         @ORM\Index(name="idx_klanten_werkgebied", columns={"werkgebied"}),
 *         @ORM\Index(name="idx_klanten_postcodegebied", columns={"postcodegebied"})
 *     }
 * )
 * @Gedmo\Loggable
 */
class Klant extends Persoon
{
    /**
     * @var Zrm[]
     *
     * @ORM\OneToMany(targetEntity="Zrm", mappedBy="klant",cascade={"persist"})
     * @ORM\OrderBy({"created" = "DESC", "id" = "DESC"})
     */
    private $zrms;

    /**
     * @var Verslag[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Verslag", mappedBy="klant")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @ORM\Column(name="laatste_TBC_controle", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $laatsteTbcControle;

    /**
     * @ORM\Column(name="last_zrm", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $laatsteZrm;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $overleden = false;

    public function __construct()
    {
        $this->zrms = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
    }

    public function getLaatsteZrm()
    {
        return $this->laatsteZrm;
    }

    public function setLaastseZrm(\DateTime $laatsteZrm)
    {
        $this->laatsteZrm = $laatsteZrm;

        return $this;
    }

    public function getLaatsteTbcControle()
    {
        return $this->laatsteTbcControle;
    }

    public function setLaatsteTbcControle($laatsteTbcControle = null)
    {
        $this->laatsteTbcControle = $laatsteTbcControle;

        return $this;
    }

    public function getZrms()
    {
        return $this->zrms;
    }

    public function addZrm(Zrm $zrm)
    {
        $this->zrms[] = $zrm;
        $zrm->setKlant($this);

        return $this;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
        $verslag->setKlant($this);

        return $this;
    }
}
