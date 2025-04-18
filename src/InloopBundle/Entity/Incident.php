<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\BaseIncident;
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
 * @Vich\Uploadable
 */
class Incident extends BaseIncident
{
}

