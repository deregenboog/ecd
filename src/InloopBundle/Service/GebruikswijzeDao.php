<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Gebruikswijze;

class GebruikswijzeDao extends AbstractDao implements GebruikswijzeDaoInterface
{
    protected $class = Gebruikswijze::class;

    protected $alias = 'gebruikswijze';

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Gebruikswijze $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Gebruikswijze $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Gebruikswijze $entity)
    {
        $this->doDelete($entity);
    }
}
