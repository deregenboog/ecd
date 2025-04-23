<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="inloop_incident_info")
 * @Gedmo\Loggable
 */
class IncidentInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Incident")
     * @ORM\JoinColumn(name="incident_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $incident;

    /**
     * @ORM\OneToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(name="locatie_id", referencedColumnName="id", nullable=false)
     */
    private $locatie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIncident(): ?Incident
    {
        return $this->incident;
    }

    public function setIncident(Incident $incident): self
    {
        $this->incident = $incident;
        return $this;
    }

    public function getLocatie(): ?Locatie
    {
        return $this->locatie;
    }

    public function setLocatie(Locatie $locatie): self
    {
        $this->locatie = $locatie;
        return $this;
    }
}
