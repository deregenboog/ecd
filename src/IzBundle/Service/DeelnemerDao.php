<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzDeelnemer;

class DeelnemerDao extends AbstractDao implements DeelnemerDaoInterface
{
    protected $class = IzDeelnemer::class;

    public function update(IzDeelnemer $entity)
    {
        $this->doUpdate($entity);
    }
}
