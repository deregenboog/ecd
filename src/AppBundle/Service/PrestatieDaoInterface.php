<?php

namespace AppBundle\Service;


interface PrestatieDaoInterface
{

    public static function getPrestatieLabel(): string;

    public function getKpis(): array;

}
