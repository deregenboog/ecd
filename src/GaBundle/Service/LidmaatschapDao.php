<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Lidmaatschap;

class LidmaatschapDao extends AbstractDao implements LidmaatschapDaoInterface
{
    protected $class = Lidmaatschap::class;

    protected $alias = 'lidmaatschap';

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
