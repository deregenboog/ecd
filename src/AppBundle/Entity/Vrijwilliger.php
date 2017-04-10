<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vrijwilligers")
 */
class Vrijwilliger extends Persoon
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $vogAangevraagd = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $vogAanwezig = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $overeenkomstAanwezig = false;

    /**
     * @return bool
     */
    public function getVogAangevraagd()
    {
        return $this->vogAangevraagd;
    }

    /**
     * @param bool $vogAangevraagd
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
    public function getVogAanwezig()
    {
        return $this->vogAanwezig;
    }

    /**
     * @param bool $vogAanwezig
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
    public function getOvereenkomstAanwezig()
    {
        return $this->overeenkomstAanwezig;
    }

    /**
     * @param bool $overeenkomstAanwezig
     * @return Vrijwilliger
     */
    public function setOvereenkomstAanwezig($overeenkomstAanwezig)
    {
        $this->overeenkomstAanwezig = $overeenkomstAanwezig;
        return $this;
    }
}
