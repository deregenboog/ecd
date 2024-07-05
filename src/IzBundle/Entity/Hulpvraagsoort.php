<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagsoortRepository")
 *
 * @ORM\Table(
 *     name="iz_hulpvraagsoorten",
 *     indexes={
 *
 *         @ORM\Index(name="id", columns={"id", "naam"})
 *     },
 *     uniqueConstraints={
 *
 *         @ORM\UniqueConstraint(name="UNIQ_86CE34D4FC4DB938", columns={"naam"})
 *     }
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Hulpvraagsoort
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

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
