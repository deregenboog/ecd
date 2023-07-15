<?php

namespace App\DagbestedingBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Verslag;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeVerslag extends Verslag
{
    public function __construct()
    {
        $this->opmerking = <<<EOF
Wie is de deelnemer? 
(Geef een beeld van de deelnemer a.d.h.v. het vliegwiel)


Wensen, motieven en reële perspectieven:

Sfeer en persoonlijkheid:

Vaardigheden, in de persoon gelegen factoren:

Sociale achtergrond en netwerken:

Manier van leren:

Wat zijn de krachten en kwaliteiten van de deelnemer?


Wat is zijn/haar motor? 
(Hulpvragen hierbij zijn o.a.: Wat vindt hij/zij het belangrijkste aan werk? Waar krijgt hij/zij energie van? Wanneer komt hij/zij trots van het werk?)


Welke aandachtspunten heeft de deelnemer? 
(Waar moet in relatie tot het werk rekening mee gehouden worden?)


Aanpak deelnemer en de betekenis van coaching 
(Bij welke aanpak ontwikkelt de deelnemer zich het best en wat betekent die aanpak voor de manier waarop hij/zij ondersteund wil worden?)


Doelen korte termijn 
(Beschrijf hier welke doelen belangrijk zijn om de doelen op lange termijn te halen. Verbind hier ook een datum of periode aan)


Doelen lange termijn 
(Welk(e) doel(en) wil de deelnemer uiteindelijk behalen, of zijn het meest haalbaar. Waar werkt de deelnemer uiteindelijk naar toe? Wanneer heeft hij/zij het doel (en beschrijf dit doel zo exact als mogelijk is) bereikt?)


Welke middelen worden ingezet en waarom? 
(Bedoeld worden die middelen die bijdragen aan het behalen van het doel, bijvoorbeeld: inzet derden (werkmakelaars, psycholoog, RIB’s enzovoort), werkleer- of stageplekken, scholing, testen, loonkostensubsidies, gewenningsperiodes, coaching, nazorg, enzovoort. Leg uit (verantwoord) waarom juist deze middelen worden ingezet)


Welke middelen | contacten worden ingezet binnen De Regenboog en waarom?
(maatje IZ, maatschappelijk werker, hulp bij zoeken van een woning, schuldhulpverlening, Uit het Krijt, OEK, groepsactiviteiten, laptop of telefoon, studiefonds, trainingen etc)


Denk aan het bespreken van de volgende thema’s 
Werk & opleiding/ financiën/ huisvesting/ gezondheid/ middelengebruik/ justitie/ talen

EOF;
    }

}
