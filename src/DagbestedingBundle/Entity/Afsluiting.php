<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\NamableTrait;
use AppBundle\Entity\ActivatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_afsluitingen")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\HasLifecycleCallbacks
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "deelnemer" = "Deelnemerafsluiting",
 *     "traject" = "Trajectafsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class Afsluiting
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

    public function isDeletable()
    {
        return false;
    }
}
