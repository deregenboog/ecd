<?php

namespace AppBundle\Entity;

use AppBundle\Model\DocumentSubjectTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="vrijwilligers",
 *     indexes={
 *         @ORM\Index(name="idx_vrijwilligers_werkgebied", columns={"werkgebied"}),
 *         @ORM\Index(name="idx_vrijwilligers_postcodegebied", columns={"postcodegebied"}),
 *         @ORM\Index(name="idx_vrijwilligers_geboortedatum", columns={"geboortedatum"})
 *     }
 * )
 * @Gedmo\Loggable
 */
class Vrijwilliger extends Persoon
{
    use DocumentSubjectTrait;

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
     *
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
     *
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

    public function getVog(): ?Vog
    {
        foreach ($this->documenten as $document) {
            if ($document instanceof Vog) {
                return $document;
            }
        }

        return null;
    }

    public function setVog(Vog $vog): self
    {
        $this->addDocument($vog);
        $this->vogAanwezig = true;

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
     *
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

    public function getOvereenkomst(): ?Overeenkomst
    {
        foreach ($this->documenten as $document) {
            if ($document instanceof Overeenkomst) {
                return $document;
            }
        }

        return null;
    }

    public function setOvereenkomst(Overeenkomst $overeenkomst): self
    {
        $this->addDocument($overeenkomst);
        $this->overeenkomstAanwezig = true;

        return $this;
    }
}
