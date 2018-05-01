<?php

namespace IzBundle\Service;

use IzBundle\Entity\Verslag;
use AppBundle\Service\AbstractDao;

class VerslagDao extends AbstractDao implements VerslagDaoInterface
{
    protected $class = Verslag::class;

    public function create(Verslag $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Verslag $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Verslag $entity)
    {
        $this->doDelete($entity);
    }
}
