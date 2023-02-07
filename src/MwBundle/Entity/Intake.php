<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Intake extends DossierStatus
{
    public function __toString(): string
    {
        $name = preg_replace(
            '/^(Intake)(\w+)/', '$1 - $2',
            (new \ReflectionClass($this))->getShortName()
        );

        return sprintf('%s op %s', $name, $this->datum->format('d-m-Y'));
    }
}
