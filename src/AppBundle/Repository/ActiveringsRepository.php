<?php

namespace AppBundle\Repository;

use AppBundle\Model\Doelstellingcijfer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Repository;
use ErOpUitBundle\Reposisory\ErOpUitRepository;
use HsBundle\Repository\KlusRepository;

class ActiveringsRepository
{
    public static function getCategoryLabel(): string
    {
        return "Activering";
    }

    public function getDoelstellingscijfers(): ?array
    {
       $doelstellingsCijfers = [];

       $doelstellingsCijfers[] = new Doelstellingcijfer(1173,"ErOpUit","Er op uit",ErOpUitRepository::class);
       $doelstellingsCijfers[] = new Doelstellingcijfer(1174,"Buurtrestaurants","Buurtrestaurants",ErOpUitRepository::class);
       $doelstellingsCijfers[] = new Doelstellingcijfer(1179,"Homeservice","Homeservice",KlusRepository::class);


    }

    public function getVerfijningsas2(): ?array
    {
        // TODO: Implement getVerfijningsas2() method.
        return null;
    }


}
