<?php

namespace InloopBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="inloop_training")
 * @ORM\HasLifecycleCallbacks
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
     * ORM\JoinColumn(name="inloopTraining_id")
     */
    private $deelnames;

    public function isDeletable()
    {
        return false;
    }
}
