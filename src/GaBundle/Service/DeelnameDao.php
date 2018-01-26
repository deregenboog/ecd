<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Deelname;

class DeelnameDao extends AbstractDao implements DeelnameDaoInterface
{
    protected $class = Deelname::class;

    protected $alias = 'deelname';

    public function create(Deelname $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Deelname $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Deelname $entity)
    {
        $this->doDelete($entity);
    }
}
