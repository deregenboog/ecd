<?php

namespace AppBundle\Model;

use AppBundle\Entity\Zrm;

interface ZrmInterface
{
    public function getZrms();

    public function addZrm(Zrm $zrm);
}
