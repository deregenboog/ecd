<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Locatietijd;

class LocatietijdDao extends AbstractDao implements LocatietijdDaoInterface
{
    protected $class = Locatietijd::class;

    protected $alias = 'locatietijd';

    public function create(Locatietijd $locatietijd)
    {
        $this->doCreate($locatietijd);
    }

    public function update(Locatietijd $locatietijd)
    {
        $this->doUpdate($locatietijd);
    }

    public function delete(Locatietijd $locatietijd)
    {
        $this->doDelete($locatietijd);
    }
}
