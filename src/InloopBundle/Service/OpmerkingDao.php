<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Opmerking;
use AppBundle\Service\AbstractDao;

class OpmerkingDao extends AbstractDao implements OpmerkingDaoInterface
{
    protected $class = Opmerking::class;

    public function create(Opmerking $entity)
    {
        return parent::doCreate($entity);
    }

    public function update(Opmerking $entity)
    {
        return parent::doUpdate($entity);
    }

    public function delete(Opmerking $entity)
    {
        return parent::doDelete($entity);
    }
}
