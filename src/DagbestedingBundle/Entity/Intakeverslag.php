<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Intakeverslag extends Verslag
{


    public function __construct()
    {
        $this->opmerking = <<<EOF
<b>Wie is de deelnemer?</b>
<br />Wensen, motieven en re&euml;le perspectieven:
<br /> <br />Sfeer en persoonlijkheid:
<br /> <br />Vaardigheden, in de persoon gelegen factoren:
<br /> <br />Sociale achtergrond en netwerken:
<br /> <br />Manier van leren:
<br /> <br />
<b>Wat zijn de krachten en kwaliteiten van de deelnemer?</b>
<br />
<b>Wat is zijn/haar motor?</b>
<br />(Hulpvragen hierbij zijn o.a.: Wat vindt hij/zij het belangrijkste aan werk? Waar krijgt hij/zij energie van? Wanneer komt hij/zij trots van het werk?)
<br /> <br />
<b>Welke aandachtspunten heeft de deelnemer?</b>
<br />(Waar moet in relatie tot het werk rekening mee gehouden worden?)
<br /> <br />
<b>Aanpak deelnemer en de betekenis van coaching</b>
<br />(Bij welke aanpak ontwikkelt de deelnemer zich het best en wat betekent die aanpak voor de manier waarop hij/zij ondersteund wil worden?)
<br /> <br />
<b>Doelen korte termijn</b>
<br />(Beschrijf hier welke doelen belangrijk zijn om de doelen op lange termijn te halen. Verbind hier ook een datum of periode aan)
<br /> <br />
<b>Doelen lange termijn</b>
<br />(Welk(e) doel(en) wil de deelnemer uiteindelijk behalen, of zijn het meest haalbaar. Waar werkt de deelnemer uiteindelijk naar toe? Wanneer heeft hij/zij het doel (en beschrijf dit doel zo exact als mogelijk is) bereikt?)
<br /> <br />
<b>Welke middelen worden ingezet en waarom?</b>
<br />(Bedoeld worden die middelen die bijdragen aan het behalen van het doel, bijvoorbeeld: inzet derden (werkmakelaars, psycholoog, RIB’s enzovoort), werkleer- of stageplekken, scholing, testen, loonkostensubsidies, gewenningsperiodes, coaching, nazorg, enzovoort. Leg uit (verantwoord) waarom juist deze middelen worden ingezet)
<br /> <br />
<b>Welke middelen | contacten worden ingezet binnen De Regenboog en waarom?</b>
<br />(maatje IZ, maatschappelijk werker, hulp bij zoeken van een woning, schuldhulpverlening, Uit het Krijt, OEK, groepsactiviteiten, laptop of telefoon, studiefonds, trainingen etc)
<br /> <br />
<b>Denk aan het bespreken van de volgende thema’s</b>
<br />Werk & opleiding/ financi&euml;n/ huisvesting/ gezondheid/ middelengebruik/ justitie/ talen

EOF;

    }
}
