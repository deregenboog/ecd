<?php

namespace AppBundle\Model;

use AppBundle\Exception\UserException;
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
        //Most of the time, the most current one is the first as it is ordered by datum DESC and id DESC
        //But, since one could add a dossierStatus with a future date, this is checked for.
        foreach($this->dossierStatussen as $ds)
        {
            if($ds->getDatum() <= new \DateTime()) return $ds;
        }

        return null;

    }

    function getMostRecentDossierStatusOfType($type)
    {
        if($this->getHuidigeDossierStatus() instanceof $type) return $this->getHuidigeDossierStatus();

        $allDossierStatussen = $this->getDossierStatussen();
        $currentPosition = array_search($this->getHuidigeDossierStatus(), $allDossierStatussen->toArray(), true);


        // Check if there is a previous DossierStatus in the array
        if ($currentPosition !== false && $currentPosition + 1 < count($allDossierStatussen)) {
            // Get the DossierStatus before the current one
            $previousDossierStatus = $allDossierStatussen[$currentPosition + 1];
            if($previousDossierStatus instanceof $type) return $previousDossierStatus;
        }
        return null;
    }

    function getPreviousDossierStatus()
    {
        $allDossierStatussen = $this->getDossierStatussen();
        // Find the position of the current DossierStatus in the array
        $currentPosition = array_search($this->getHuidigeDossierStatus(), $allDossierStatussen->toArray(), true);

        // Check if there is a previous DossierStatus in the array
        if ($currentPosition !== false && $currentPosition + 1 < count($allDossierStatussen)) {
            // Get the DossierStatus before the current one
            $previousDossierStatus = $allDossierStatussen[$currentPosition + 1];

            return $previousDossierStatus;
        }

        // Return null if there is no previous DossierStatus
        return null;
    }

    function getNextDossierStatus()
    {
        $allDossierStatussen = $this->getDossierStatussen();
        // Find the position of the current DossierStatus in the array
        $currentPosition = array_search($this->getHuidigeDossierStatus(), $allDossierStatussen->toArray(), true);

        // Check if there is a previous DossierStatus in the array
        if ($currentPosition !== false && $currentPosition - 1 < count($allDossierStatussen)) {
            // Get the DossierStatus before the current one
            $previousDossierStatus = $allDossierStatussen[$currentPosition - 1];

            return $previousDossierStatus;
        }

        // Return null if there is no next DossierStatus
        return null;
    }

    public function getDossierStatusById(int $statusId): ?DossierStatusInterface
    {
        foreach($this->getDossierStatussen() as $ds){
            if($ds->getId() === $statusId)
            {
                return $ds;
            }
        }
        return null;
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
