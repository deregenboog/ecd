<?php

namespace OekraineBundle\Service;

use AppBundle\Service\AbstractDao;
use OekraineBundle\Entity\Incident;

class IncidentDao extends AbstractDao implements IncidentDaoInterface
{
    protected $class = Incident::class;

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function create(Incident $entity)
    {
        return $this->doCreate($entity);
    }

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function update(Incident $entity)
    {
        return $this->doUpdate($entity);
    }

    /**
     * @param Incident
     *
     * @return Incident
     */
    public function delete(Incident $entity)
    {
        return $this->doDelete($entity);
    }
}
