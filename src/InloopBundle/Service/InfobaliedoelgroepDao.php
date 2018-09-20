<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Infobaliedoelgroep;

class InfobaliedoelgroepDao extends AbstractDao implements InfobaliedoelgroepDaoInterface
{
    protected $class = Infobaliedoelgroep::class;

    protected $alias = 'infobaliedoelgroep';

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
    public function create(Infobaliedoelgroep $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Infobaliedoelgroep $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Infobaliedoelgroep $entity)
    {
        $this->doDelete($entity);
    }
}
