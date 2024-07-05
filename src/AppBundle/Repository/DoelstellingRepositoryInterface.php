<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Doelstelling;
use AppBundle\Model\Doelstellingcijfer;

interface DoelstellingRepositoryInterface
{
    public const CAT_ACTIVERING = 'Activering';
    public const CAT_IZ = 'Informele zorg';
    public const CAT_INLOOP = 'Inloop';
    public const CAT_HULPVERLENING = 'Hulpverlening';
    public const CAT_INTERNATIONAAL = 'Internationaal';
    public const CAT_OVERIG = 'Overig';
    public const CAT_SCIP = 'SCIP';

    //    public function getMethods(): array;
    public function getCategory(): string;

    public function initDoelstellingcijfers(): void;

    public function getAvailableDoelstellingcijfers(): array;

    public function getDoelstelingcijfer($name): Doelstellingcijfer;
    //
    //    public static function getCategoryLabel(): string;
    //
    //    public function getVerfijningsas1(): ?array;
    //    public function getVerfijningsas2(): ?array;
    //
    //    public function getCurrentNumber(Doelstelling $doelstelling): string;
}
