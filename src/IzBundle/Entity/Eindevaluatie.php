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
Terugblik: hoe hebben de deelnemer en de vrijwilliger het contact ervaren?


Was de deelnemer tevreden over hoe het maatje hem/haar benaderde en met hem/haar omging?


Heeft het contact aan de verwachtingen voldaan? Waarom wel/niet?


Wat was het doel van het contact en welke effecten ervaart de deelnemer? (*)


Is er voor de deelnemer (nog iets anders) veranderd in positieve zin door het contact met de vrijwilliger? Zo ja, wat?


Hoe gaat het nu met de deelnemer? Is vervolgondersteuning nodig?


Welk cijfer geeft de deelnemer het contact met je laatste maatje? Graag een toelichting.


Ben je tevreden over het contact met de coördinator?


Vragen specifiek aan de vrijwilliger: met wel cijfer beoordeel je je vrijwilligerswerk bij ons? Wat maakt dat je ons dat cijfer geeft?


EOF;
    }
}
