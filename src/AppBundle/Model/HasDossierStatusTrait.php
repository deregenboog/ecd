<?php

namespace AppBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


trait HasDossierStatusTrait
{
    public function initializeTrait()
    {
        $this->dossierStatussen = new ArrayCollection();
    }

    public function getDossierStatussen(): Collection
    {
        return $this->dossierStatussen;
    }

    public function getDossierStatus(): ?DossierStatusInterface
    {
        return $this->getHuidigeDossierStatus();
    }

    public function getHuidigeDossierStatus(): ?DossierStatusInterface
    {
        // relying on @ORM\OrderBy annotation based on DESC datum, DESC id.
        return $this->dossierStatussen->first();
    }

    public function addDossierStatus(DossierStatusInterface $dossierStatus): self
    {
        if (!$this->dossierStatussen->contains($dossierStatus)) {
            $dossierStatus->setEntity($this);
            $this->dossierStatussen[] = $dossierStatus;
        }

        return $this;
    }

    public function removeDossierStatus(DossierStatusInterface $dossierStatus): void
    {
        if (!$this->dossierStatussen->contains($dossierStatus)) {
            $this->dossierStatussen->removeElement($dossierStatus);
            if ($dossierStatus->getEntity() === $this) {
                $dossierStatus->setEntity(null);
            }
        }
    }
}
