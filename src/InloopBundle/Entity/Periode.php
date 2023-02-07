<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimeframeableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="verslavingsperiodes")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Periode
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimeframeableTrait;
    use TimestampableTrait;

    public function __construct()
    {
        $this->datumVan = new \DateTime();
    }

    public function isDeletable()
    {
        return false;
    }
}
