<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\VrijwilligerDeelname;

class VrijwilligerDeelnameDao extends AbstractDao implements VrijwilligerDeelnameDaoInterface
{
    protected $class = VrijwilligerDeelname::class;

    protected $alias = 'deelname';

    public function create(VrijwilligerDeelname $entity)
    {
        $this->doCreate($entity);
    }

    public function update(VrijwilligerDeelname $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(VrijwilligerDeelname $entity)
    {
        $this->doDelete($entity);
    }
}
