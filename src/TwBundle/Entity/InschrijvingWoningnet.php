<?php

namespace TwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="tw_inschrijvingwoningnet")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class InschrijvingWoningnet
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="label", type="string")
     *
     * @Gedmo\Versioned
     */
    private $label;

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

    /**
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

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label): void
    {
        $this->label = $label;
    }
}
