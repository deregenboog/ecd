<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\GaGroep;
use GaBundle\Entity\GaLidmaatschap;

abstract class LidmaatschapDao extends AbstractDao implements LidmaatschapDaoInterface
{
    protected $alias = 'lidmaatschap';

    public function findByGroep(GaGroep $groep, $page = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->where($this->alias.'.groep = :groep')
            ->setParameter('groep', $groep)
        ;

        return $this->doFindAll($builder, $page);
    }

    public function create(GaLidmaatschap $entity)
    {
        $this->doCreate($entity);
    }

    public function update(GaLidmaatschap $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(GaLidmaatschap $entity)
    {
        $this->doDelete($entity);
    }
}
