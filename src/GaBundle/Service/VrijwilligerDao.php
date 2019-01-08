<?php

namespace GaBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\VrijwilligerIntake;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, intake, medewerker, werkgebied")
            ->leftJoin(VrijwilligerIntake::class, 'intake', 'WITH', 'intake.vrijwilliger = vrijwilliger')
            ->leftJoin('intake.medewerker', 'medewerker')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    public function create(Vrijwilliger $entity)
    {
        return $this->doCreate($entity);
    }

    public function update(Vrijwilliger $entity)
    {
        return $this->doUpdate($entity);
    }

    public function delete(Vrijwilliger $entity)
    {
        return $this->doDelete($entity);
    }
}
