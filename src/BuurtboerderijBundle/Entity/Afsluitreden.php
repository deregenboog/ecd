<?php

namespace BuurtboerderijBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table("buurtboerderij_afsluitredenen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluitreden
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait;
}
