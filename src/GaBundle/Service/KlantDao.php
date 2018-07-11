<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\KlantIntake;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $class = Klant::class;

    protected $alias = 'klant';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, intake, medewerker, werkgebied")
            ->leftJoin(KlantIntake::class, 'intake', 'WITH', 'intake.klant = klant')
            ->leftJoin('intake.medewerker', 'medewerker')
            ->leftJoin('klant.werkgebied', 'werkgebied')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }
}
