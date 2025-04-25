<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Incident as BaseIncident;
use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\IncidentInterface;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class DienstverlenersIncident extends BaseIncident
{
    public function getIncidentType(): string
    {
        return 'hs_dienstverleners';
    }
}
