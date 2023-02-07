<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\BinnenVia;
use MwBundle\Entity\BinnenViaOptieVrijwilliger;

class BinnenViaVrijwilligerDao extends AbstractDao implements BinnenViaDaoInterface
{
    protected $class = BinnenViaOptieVrijwilliger::class;

    protected $alias = 'binnenVia';

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
    public function create(BinnenVia $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(BinnenVia $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(BinnenVia $entity)
    {
        $this->doDelete($entity);
    }
}
