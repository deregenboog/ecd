<?php

namespace TwBundle\Service;


use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;

use TwBundle\Entity\Deelname;
use OekBundle\Entity\DeelnameStatus;


class DeelnameDao extends AbstractDao implements DeelnameDaoInterface
{
    protected $class = Deelname::class;

    protected $alias = 'deelname';

    /**
     * {inheritdoc}.
     */
    public function create(Deelname $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Deelname $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Deelname $entity)
    {
        $this->doDelete($entity);
    }
}
