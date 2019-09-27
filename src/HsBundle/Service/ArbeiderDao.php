<?php

namespace HsBundle\Service;

use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Arbeider;

class ArbeiderDao extends AbstractDao implements ArbeiderDaoInterface
{
    protected $class = Arbeider::class;

    protected $alias = 'arbeider';
}
