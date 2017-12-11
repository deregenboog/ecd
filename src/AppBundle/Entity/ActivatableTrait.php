<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait ActivatableTrait
{
    /**
     * @var bool
     * @ORM\Column(name="active", type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    protected $actief = true;

    public function isActief()
    {
        return $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = (bool) $actief;

        return $this;
    }
}
