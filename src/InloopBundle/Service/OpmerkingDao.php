<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Locatie;
use AppBundle\Entity\Opmerking;
use Doctrine\ORM\Query\Expr;
use InloopBundle\Entity\RecenteOpmerking;
use InloopBundle\Filter\OpmerkingFilter;
use InloopBundle\Entity\Aanmelding;

class OpmerkingDao extends AbstractDao implements OpmerkingDaoInterface
{
    protected $class = Opmerking::class;

    public function create(Opmerking $entity)
    {
        return parent::doCreate($entity);
    }

    public function update(Opmerking $entity)
    {
        return parent::doUpdate($entity);
    }

    public function delete(Opmerking $entity)
    {
        return parent::doDelete($entity);
    }
}
