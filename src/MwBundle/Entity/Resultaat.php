<?php

namespace MwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Resultaat
 * @package MwBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="mw_resultaten")
 * @Gedmo\Loggable
 */
class Resultaat
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
}
