<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Contactpersoon;

class ContactpersoonDao extends AbstractDao implements ContactpersoonDaoInterface
{
    protected $class = Contactpersoon::class;

    protected $alias = 'contactpersoon';

    public function create(Contactpersoon $contactpersoon)
    {
        $this->doCreate($contactpersoon);
    }

    public function update(Contactpersoon $contactpersoon)
    {
        $this->doUpdate($contactpersoon);
    }

    public function delete(Contactpersoon $contactpersoon)
    {
        $this->doDelete($contactpersoon);
    }
}
