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
     * @ORM\Column(name="vog_aanwezig", type="boolean")
     * @Gedmo\Versioned
     */
    protected $vogAanwezig = false;

    /**
     * @ORM\Column(name="overeenkomst_aanwezig", type="boolean")
     * @Gedmo\Versioned
     */
    protected $overeenkomstAanwezig = false;

    /**
     * @return bool
     */
    public function isVogAangevraagd()
    {
        return $this->vogAangevraagd;
    }

    /**
     * @return bool
     * @deprecated
     */
    public function getVogAangevraagd()
    {
        return $this->isVogAangevraagd();
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
     * @return bool
     */
    public function isVogAanwezig()
    {
        return $this->vogAanwezig;
    }

    /**
     * @return bool
     * @deprecated
     */
    public function getVogAanwezig()
    {
        return $this->isVogAanwezig();
    }

    /**
     * @param bool $vogAanwezig
     *
     * @return Vrijwilliger
     */
    public function setVogAanwezig($vogAanwezig)
    {
        $this->vogAanwezig = $vogAanwezig;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOvereenkomstAanwezig()
    {
        return $this->overeenkomstAanwezig;
    }

    /**
     * @return bool
     * @deprecated
     */
    public function getOvereenkomstAanwezig()
    {
        return $this->isOvereenkomstAanwezig();
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
}
