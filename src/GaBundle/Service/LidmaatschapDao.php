<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Entity\Groep;
use GaBundle\Entity\VrijwilligerLidmaatschap;

abstract class LidmaatschapDao extends AbstractDao implements LidmaatschapDaoInterface
{
    protected $alias = 'lidmaatschap';

    public function findByGroep(Groep $groep, $page = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->where($this->alias.'.groep = :groep')
            ->setParameter('groep', $groep)
        ;

        return $this->doFindAll($builder, $page);
    }

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
