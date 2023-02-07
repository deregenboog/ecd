<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\DossierStatus;

class DossierStatusDao extends AbstractDao
{
    protected $class = DossierStatus::class;

    protected $alias = 'dossierstatus';

    public function create(DossierStatus $dossierStatus)
    {
        return $this->doCreate($dossierStatus);
    }

    public function update(DossierStatus $dossierStatus)
    {
        return $this->doUpdate($dossierStatus);
    }
}
