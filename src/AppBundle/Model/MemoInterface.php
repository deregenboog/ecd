<?php

namespace AppBundle\Model;

use AppBundle\Entity\Medewerker;

interface MemoInterface
{
    public function getDatum();

    public function setDatum(\DateTime $datum);

    public function getMedewerker();

    public function setMedewerker(Medewerker $medewerker);

    public function getOnderwerp();

    public function setOnderwerp($onderwerp);

    public function getMemo();

    public function setMemo($memo);
}
