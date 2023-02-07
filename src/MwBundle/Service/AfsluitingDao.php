<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Afsluiting;

class AfsluitingDao extends AbstractDao
{
    protected $class = Afsluiting::class;

    protected $alias = 'afsluiting';

    public function create(Afsluiting $afsluiting)
    {
        return $this->doCreate($afsluiting);
    }
}
