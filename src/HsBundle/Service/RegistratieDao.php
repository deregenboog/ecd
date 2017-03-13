<?php

namespace HsBundle\Service;

use HsBundle\Entity\Registratie;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class RegistratieDao extends AbstractDao implements RegistratieDaoInterface
{
    protected $class = Registratie::class;

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        return parent::findAll($page);
    }

    /**
     * {inheritdoc}
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}
     */
    public function create(Registratie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Registratie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Registratie $entity)
    {
        $this->doDelete($entity);
    }
}
