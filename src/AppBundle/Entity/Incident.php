<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\IncidentInterface;
use AppBundle\Model\IncidentTrail;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="inloop_incidenten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Incident implements IncidentInterface
{
    use IncidentTrail;   

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     */
    private $locatie;
    
    public function getLocatie()
    {
        return $this->locatie;
    }

    /**
     * @return Incident
     */
    public function setLocatie($locatie)
    {
        $this->locatie = $locatie;

        return $this;
    }
}
