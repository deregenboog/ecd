<?php

namespace AppBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\NameTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="categorieen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Categorie
{
    use IdentifiableTrait, NameableTrait, TimestampableTrait;
}
