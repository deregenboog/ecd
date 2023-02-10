<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait ActivatableTrait
{
    /**
     * @var bool
     * @ORM\Column(name="`active`", type="boolean")
     * @Gedmo\Versioned
     */
    protected $actief = true;

    public function isActief()
    {
        return (bool) $this->actief;
    }

    public function getActief()
    {
        return $this->actief;
    }

    public function setActief(bool $actief)
    {
        $this->actief = $actief;

        return $this;
    }
}
