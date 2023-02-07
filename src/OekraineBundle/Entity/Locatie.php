<?php

namespace OekraineBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimeframeableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="oekraine_locaties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Locatie
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimeframeableTrait;
    use TimestampableTrait;

    public function isOpen(\DateTime $date = null)
    {
        return true;
    }

    public function isDeletable()
    {
        return false;
    }

    public function isActief(): bool
    {
        $now = new \DateTime("now");

        if ($this->datumTot == null || ($this->datumVan < $now && $this->datumTot > $now)) {
            return true;
        }
        return false;
    }
}
