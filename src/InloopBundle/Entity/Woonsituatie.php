<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimeframeableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="woonsituaties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Woonsituatie
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimeframeableTrait;
    use TimestampableTrait;
    use NotDeletableTrait;
}
