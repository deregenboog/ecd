<?php

namespace AppBundle\Repository;


interface DoelstellingRepositoryInterface
{

    public static function getPrestatieLabel(): string;

    public function getKpis(): array;

}
