<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Tussenevaluatie extends Verslag
{
    public function __construct()
    {
        $this->opmerking = <<<EOF
Terugblik: hoe hebben de deelnemer en de vrijwilliger het contact ervaren?


Hoe vaak zien zij elkaar


Hoe gaat het met de deelnemer?


Indien van toepassing: is er gewerkt aan de gestelde doelen, wat is de stand van zaken?


Is er iets veranderd in de situatie van de vrijwilliger wat gevolgen kan hebben voor het contact?


Waar komt in de komende periode het accent op te liggen?


EOF;
    }
}
