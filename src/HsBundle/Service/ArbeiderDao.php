<?php

namespace HsBundle\Service;

use HsBundle\Entity\Arbeider;
use AppBundle\Service\AbstractDao;

class ArbeiderDao extends AbstractDao implements ArbeiderDaoInterface
{
    protected $class = Arbeider::class;

    protected $alias = 'arbeider';
}
