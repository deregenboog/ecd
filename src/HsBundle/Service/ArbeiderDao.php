<?php

namespace HsBundle\Service;

use HsBundle\Entity\Arbeider;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;

class ArbeiderDao extends AbstractDao implements ArbeiderDaoInterface
{
    protected $class = Arbeider::class;

    protected $alias = 'arbeider';
}
