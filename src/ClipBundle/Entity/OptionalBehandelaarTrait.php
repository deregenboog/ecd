<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait OptionalBehandelaarTrait
{
    /**
     * @var Behandelaar
     *
     * @ORM\ManyToOne(targetEntity="Behandelaar")
     *
     * @Gedmo\Versioned
     */
    protected $behandelaar;

    public function getBehandelaar(): ?Behandelaar
    {
        return $this->behandelaar;
    }

    public function setBehandelaar(?Behandelaar $behandelaar)
    {
        $this->behandelaar = $behandelaar;

        return $this;
    }
}
