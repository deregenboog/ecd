<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Incident as BaseIncident;
use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\IncidentInterface;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use MwBundle\Entity\IncidentInfo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Incident extends BaseIncident
{
    /**
     * @var ?IncidentInfo
     * @ORM\OneToOne(targetEntity="MwBundle\Entity\IncidentInfo", mappedBy="incident", cascade={"persist", "remove"})
     */
    private $incidentInfo;

    public function getIncidentType(): string
    {
        return 'mw';
    }

    public function getIncidentInfo(): ?IncidentInfo
    {
        return $this->incidentInfo;
    }

    public function setIncidentInfo(?IncidentInfo $incidentInfo): self
    {
        $this->incidentInfo = $incidentInfo;

        // Set the owning side of the relation
        if ($incidentInfo !== null && $incidentInfo->getIncident() !== $this) {
            $incidentInfo->setIncident($this);
        }

        return $this;
    }

    public function getLocatie():string {
        if ($this->incidentInfo !== null && $this->incidentInfo->getLocatie() !== null) {
            return $this->incidentInfo->getLocatie()->getNaam();
        }
        return "-----";
    }
}
