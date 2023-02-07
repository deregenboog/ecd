<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Aanmelding;

class AanmeldingDao extends AbstractDao
{
    protected $class = Aanmelding::class;

    protected $alias = 'aanmelding';

    public function create(Aanmelding $aanmelding)
    {
        return $this->doCreate($aanmelding);
    }
}
