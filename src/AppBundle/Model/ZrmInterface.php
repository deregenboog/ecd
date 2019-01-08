<?php

namespace AppBundle\Model;

use AppBundle\Entity\Zrm;

interface ZrmInterface
{
    public function getId();

    public function getZrm();

    public function setZrm(Zrm $zrm);
}
