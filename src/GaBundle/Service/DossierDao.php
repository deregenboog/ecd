<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Dossier;

class DossierDao extends AbstractDao implements DossierDaoInterface
{
    protected $class = Dossier::class;

    public function update(Dossier $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Dossier $entity)
    {
        $this->doDelete($entity);
    }
}
