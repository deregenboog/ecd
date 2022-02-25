<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Doelstelling;
use AppBundle\Model\Doelstellingcijfer;

interface DoelstellingRepositoryInterface
{
    public CONST CAT_ACTIVERING = 'Activering';
    public CONST CAT_IZ = 'Informele zorg';
    public CONST CAT_INLOOP = 'Inloop';
    public CONST CAT_HULPVERLENING = 'Hulpverlening';
    public CONST CAT_INTERNATIONAAL = 'Internationaal';
    public CONST CAT_OVERIG = 'Overig';
    public CONST CAT_SCIP = 'SCIP';



//    public function getMethods(): array;
    public function getCategory(): string;
    public function initDoelstellingcijfers():void;
    public function getAvailableDoelstellingcijfers(): array;
    public function getDoelstelingcijfer($name):Doelstellingcijfer;
//
//    public static function getCategoryLabel(): string;
//
//    public function getVerfijningsas1(): ?array;
//    public function getVerfijningsas2(): ?array;
//
//    public function getCurrentNumber(Doelstelling $doelstelling): string;


}
