<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait IdentifiableTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
