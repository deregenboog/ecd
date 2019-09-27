<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

    public function isDeletable()
    {
        return false;
    }
}
