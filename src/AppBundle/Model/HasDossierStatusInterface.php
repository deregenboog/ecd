<?php

namespace AppBundle\Model;

use Doctrine\Common\Collections\Collection;


interface HasDossierStatusInterface
{
    public function getDossierStatussen(): Collection;


    public function getDossierStatus(): ?DossierStatusInterface;


    public function getHuidigeDossierStatus(): ?DossierStatusInterface;


    public function addDossierStatus(DossierStatusInterface $dossierStatus): self;


    public function removeDossierStatus(DossierStatusInterface $dossierStatus): void;
}
