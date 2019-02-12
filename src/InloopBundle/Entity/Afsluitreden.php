<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\NotDeletableTrait;

/**
 * @ORM\Entity
 * @ORM\Table("inloop_afsluitredenen_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluitreden
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait;
}
