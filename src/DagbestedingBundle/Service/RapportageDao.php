<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Rapportage;
use AppBundle\Filter\FilterInterface;

class RapportageDao extends AbstractDao implements RapportageDaoInterface
{
    protected $class = Rapportage::class;

    protected $alias = 'rapportage';

    public function create(Rapportage $rapportage)
    {
        $this->doCreate($rapportage);
    }

    public function update(Rapportage $rapportage)
    {
        $this->doUpdate($rapportage);
    }

    public function delete(Rapportage $rapportage)
    {
        $this->doDelete($rapportage);
    }
}
