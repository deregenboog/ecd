<?php

namespace TwBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_inkomen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Inkomen
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="label", type="string", nullable=false)
     * @Gedmo\Versioned
     */
    private $label;

    /**
     * @ORM\Column(name="order", type="integer", nullable=false)
     * @ORM\GeneratedValue
     * @Gedmo\Versioned
     */
    private $order;

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     * @return InschrijvingWoningnet
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }


    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }




}
