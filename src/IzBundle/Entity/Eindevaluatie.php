<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Eindevaluatie extends Verslag
{
    public function __construct()
    {
        $this->opmerking = <<<EOF
Wat is het allerleukste geweest van het afgelopen half jaar?


Waar ben je nog meer tevreden over?


Hoe is het contact verlopen met je vrijwilliger/deelnemer en coördinator? Tips voor de regenboog of je coördinator wat anders zou kunnen?


Wat heeft het je opgeleverd? Zijn er dingen waar je nu (verder) mee aan de slag wil?


Heeft het contact aan je verwachtingen voldaan? Op welke punten wel/niet?


Ben je door dingen of door jezelf verrast het afgelopen half jaar?


Wat wordt het doel vd koppeling als er een verlenging komt? Indien de koppeling stopt bij de regenboog: blijven jullie elkaar zien?


EOF;
    }
}
