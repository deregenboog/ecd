<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait OrderableTrait
{
    /**
     * @ORM\Column(name="`order`", type="integer")
     *
     * @ORM\GeneratedValue
     *
     * @Gedmo\Versioned
     */
    private $order;

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }
}
