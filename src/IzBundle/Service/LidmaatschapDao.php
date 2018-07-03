<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Lidmaatschap;

class LidmaatschapDao extends AbstractDao implements LidmaatschapDaoInterface
{
    protected $class = Lidmaatschap::class;

    public function create(Lidmaatschap $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Lidmaatschap $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Lidmaatschap $entity)
    {
        $this->doDelete($entity);
    }
}
