<?php

namespace AppBundle\Model;

use AppBundle\Entity\Zrm;

interface ZrmsInterface
{
    public function getId();

    public function getZrms();

    public function addZrm(Zrm $zrm);
}
