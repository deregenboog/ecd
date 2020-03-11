<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="infobaliedoelgroepen")
 * @Gedmo\Loggable
 */
class Infobaliedoelgroep
{
    use IdentifiableTrait;
    use NameableTrait;
    use NotDeletableTrait;
}
