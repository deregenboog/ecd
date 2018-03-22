<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\ActivatableTrait;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagsoortRepository")
 * @ORM\Table(
 *     name="iz_hulpvraagsoorten",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"naam"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Hulpvraagsoort
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $toelichting;

    public function __toString()
    {
        return $this->naam;
    }

    public function getToelichting()
    {
        return $this->toelichting;
    }

    public function setToelichting($toelichting = null)
    {
        $this->toelichting = $toelichting;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
