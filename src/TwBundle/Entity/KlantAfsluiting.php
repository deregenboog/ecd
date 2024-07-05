<?php

namespace TwBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @Gedmo\Loggable
 */
class KlantAfsluiting extends Afsluiting
{
    /**
     * @var Klant
     *
     * @ORM\OneToMany(targetEntity="Klant", mappedBy="afsluiting")
     */
    protected $huurders;

    public function __construct()
    {
        $this->huurders = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === (is_array($this->huurders) || $this->huurders instanceof \Countable ? count($this->huurders) : 0);
    }
}
