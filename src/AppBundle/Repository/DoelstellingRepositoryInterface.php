<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Doelstelling;

interface DoelstellingRepositoryInterface
{
    CONST CAT_ACTIVERING = 'Activering';
    CONST CAT_IZ = 'Informele zorg';
    CONST CAT_INLOOP = 'Inloop';
    CONST CAT_HULPVERLENING = 'Hulpverlening';
    CONST CAT_INTERNATIONAAL = 'Internationaal';
    CONST CAT_OVERIG = 'Overig';
    CONST CAT_SCIP = 'SCIP';

    public function getMethods(): array;
    public function getCategory(): string;
//
//    public static function getCategoryLabel(): string;
//
//    public function getVerfijningsas1(): ?array;
//    public function getVerfijningsas2(): ?array;
//
//    public function getCurrentNumber(Doelstelling $doelstelling): string;


}
