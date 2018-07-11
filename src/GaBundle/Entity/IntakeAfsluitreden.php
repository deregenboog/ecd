<?php

namespace GaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_afsluitingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeAfsluitreden
{
    use IdentifiableTrait, NameableTrait, TimestampableTrait;
}
