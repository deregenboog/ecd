<?php

namespace AppBundle\Service;


interface DoelstellingDaoInterface
{

    public static function getPrestatieLabel(): string;

    public function getKpis(): array;

}
