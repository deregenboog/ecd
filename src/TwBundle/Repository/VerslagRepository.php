<?php

namespace TwBundle\Repository;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityRepository;


class VerslagRepository extends EntityRepository
{
    public function findAll()
    {
        $builder = $this->createQueryBuilder("verslag");
        $builder->where("verslag INSTANCE OF Verslag");

        return $builder->getQuery()->getResult();
    }
}
