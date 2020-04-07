<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Doelstelling;

interface DoelstellingRepositoryInterface
{

    public static function getPrestatieLabel(): string;

    public function getKpis(): array;

    public function getCurrentNumber(Doelstelling $doelstelling): string;

}
