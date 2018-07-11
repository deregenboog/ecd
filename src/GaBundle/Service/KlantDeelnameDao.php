<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\KlantDeelname;

class KlantDeelnameDao extends AbstractDao implements KlantDeelnameDaoInterface
{
    protected $class = KlantDeelname::class;

    protected $alias = 'deelname';

    public function create(KlantDeelname $entity)
    {
        $this->doCreate($entity);
    }

    public function update(KlantDeelname $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(KlantDeelname $entity)
    {
        $this->doDelete($entity);
    }
}
