<?php

namespace AppBundle\Model;

use AppBundle\Entity\Zrm;

interface ZrmInterface
{
    public function getZrm();

    public function setZrm(Zrm $zrm);
}
