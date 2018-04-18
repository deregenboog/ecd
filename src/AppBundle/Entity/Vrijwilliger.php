<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="vrijwilligers",
 *     indexes={
 *         @ORM\Index(name="idx_vrijwilligers_werkgebied", columns={"werkgebied"}),
 *         @ORM\Index(name="idx_vrijwilligers_postcodegebied", columns={"postcodegebied"})
 *     }
 * )
 * @Gedmo\Loggable
 */
class Vrijwilliger extends Persoon
{
    /**
     * @ORM\Column(name="vog_aangevraagd", type="boolean")
     * @Gedmo\Versioned
     */
    protected $vogAangevraagd = false;

    /**
     * @ORM\Column(name="vog_aanwezig", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $vogAanwezig;

    /**
     * @ORM\Column(name="overeenkomst_aanwezig", type="boolean")
     * @Gedmo\Versioned
     */
    protected $overeenkomstAanwezig = false;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $datumSiaCursus;

    /**
     * @return bool
     */
    public function getVogAangevraagd()
    {
        return $this->vogAangevraagd;
    }

    /**
     * @param bool $vogAangevraagd
     *
     * @return Vrijwilliger
     */
    public function setVogAangevraagd($vogAangevraagd)
    {
        $this->vogAangevraagd = $vogAangevraagd;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getVogAanwezig()
    {
        return $this->vogAanwezig;
    }

    /**
     * @param \DateTime $vogAanwezig
     *
     * @return Vrijwilliger
     */
    public function setVogAanwezig(\DateTime $vogAanwezig = null)
    {
        $this->vogAanwezig = $vogAanwezig;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOvereenkomstAanwezig()
    {
        return $this->overeenkomstAanwezig;
    }

    /**
     * @param bool $overeenkomstAanwezig
     *
     * @return Vrijwilliger
     */
    public function setOvereenkomstAanwezig($overeenkomstAanwezig)
    {
        $this->overeenkomstAanwezig = $overeenkomstAanwezig;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatumSiaCursus()
    {
        return $this->datumSiaCursus;
    }

    /**
     * @param \DateTime $datumSiaCursus
     *
     * @return Vrijwilliger
     */
    public function setDatumSiaCursus(\DateTime $datumSiaCursus = null)
    {
        $this->datumSiaCursus = $datumSiaCursus;

        return $this;
    }

}
