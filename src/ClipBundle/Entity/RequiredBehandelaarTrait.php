<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait RequiredBehandelaarTrait
{
    /**
     * @var Behandelaar
     *
     * @ORM\ManyToOne(targetEntity="Behandelaar")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $behandelaar;

    public function getBehandelaar()
    {
        return $this->behandelaar;
    }

    public function setBehandelaar(Behandelaar $behandelaar)
    {
        $this->behandelaar = $behandelaar;

        return $this;
    }
}
