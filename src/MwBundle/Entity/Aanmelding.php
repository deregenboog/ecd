<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_aanmeldingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Aanmelding extends DossierStatus
{
    public function __toString()
    {
        if ($this->project) {
            return sprintf(
                'Aanmelding bij "%s" op %s',
                $this->project,
                $this->datum->format('d-m-Y')
            );
        }

        return sprintf(
            'Aanmelding op %s',
            $this->datum->format('d-m-Y')
        );
    }

    /**
     * @var BinnenViaOptieKlant
     *
     * @ORM\ManyToOne(targetEntity="BinnenViaOptieKlant")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $binnenVia;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @Gedmo\Versioned
     */
    protected $project;

    public function getBinnenVia(): ?BinnenViaOptieKlant
    {
        return $this->binnenVia;
    }

    public function setBinnenVia(BinnenViaOptieKlant $binnenVia): self
    {
        $this->binnenVia = $binnenVia;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): Aanmelding
    {
        $this->project = $project;

        return $this;
    }
}
