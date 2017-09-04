<?php

namespace HsBundle\Service;

use HsBundle\Entity\Registratie;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use HsBundle\Entity\Arbeider;

class RegistratieDao extends AbstractDao implements RegistratieDaoInterface
{
    protected $class = Registratie::class;

    protected $alias = 'registratie';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        return parent::findAll($page, $filter);
    }

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
    public function create(Registratie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Registratie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Registratie $entity)
    {
        $this->doDelete($entity);
    }
}
