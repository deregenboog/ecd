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
Wat is je ervaring met je coördinator? Heb je voldoende begeleiding gehad?


Terugblik: Hoe hebben de deelnemer en vrijwilliger het contact ervaren? Hoe vaak zien zij elkaar?


Hoe gaat het met de deelnemer?


Is er gewerkt aan de gestelde doelen en wat is de stand van zaken?


Is er iets veranderd in de situatie van de vrijwilliger of deelnemer wat gevolgen kan hebben voor het contact?


Waar komt in de komende periode het accent op te liggen?


EOF;
    }
}
