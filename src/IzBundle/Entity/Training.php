<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="iz_training")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Training
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    /**
     * @var Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", cascade={"persist"}, mappedBy="training")
     * ORM\JoinColumn(name="izTraining_id")
     */
    private $deelnames;

    public function isDeletable()
    {
        return false;
    }
}
