<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Groep;

abstract class LidmaatschappenController extends AbstractChildController
{
    protected $title = 'Lidmaatschappen';
    protected $entityName = 'lidmaatschap';
    protected $disabledActions = ['delete'];
    protected $allowEmpty = true;

    protected function createEntity($parentEntity = null)
    {
        if ($parentEntity instanceof Groep) {
            return new $this->entityClass($parentEntity);
        }

        return new $this->entityClass(null, $parentEntity);
    }

    protected function persistEntity($entity, $parentEntity)
    {
        $this->dao->create($entity);
    }
}
