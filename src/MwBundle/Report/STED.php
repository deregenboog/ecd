<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Report\AbstractMwReport;
use MwBundle\Service\KlantDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\VerslagDao;

class STED extends AbstractMwReport
{
    protected $title = 'STED';

    protected function filterLocations($allLocations)
    {
        $this->locaties = [];
        /**
         * Filter: alleen 'zonder zorg'
         */
        foreach($allLocations as $locatie)
        {
            $naam = $locatie->getNaam();
            if(
                (
                    strpos($naam, "STED ") !== false
                    || strpos($naam,"Wachtlijst STED") !== false
                )
                && strpos($naam, "Amstelland") === false
            ) {
                $this->locaties[] = $locatie->getNaam();
            }
        }
    }

    protected function init()
    {
        //Haal klantenIds op die actief waren in de periode. Dus ze waren actief en zijn afgesloten, of ze zijn aangemeld en weer afgesloten, of nog niet afgesloten.
        //Bruikbaar in andere queries.
        $this->actieveKlanten = $this->mdsDao->getActiveKlantIdsForPeriod($this->startDate,$this->endDate);

        /**
         * Oude manier van rapporteren is om verslagen te tellen. Dit hield geen rekening met dossierstatus.
         * Daarom actieveKlanten toegevoegd.
         */
        $this->resultKlantenVerslagen = $this->dao->countUniqueKlantenEnGezinnenVoorLocaties($this->startDate, $this->endDate, $this->locaties, $this->actieveKlanten);
        $this->resultKlantenVerslagenTotalUnique = $this->dao->getTotalUniqueKlantenEnGezinnenForLocaties($this->startDate,$this->endDate,$this->locaties, $this->actieveKlanten);

        /**
         * Om aansluiting te houden bij het verleden ook dezelfde query als vroeger maar dan nu zonder actieveKlanten.
         */
        $this->resultKlantenVerslagenWOActief = $this->dao->countUniqueKlantenEnGezinnenVoorLocaties($this->startDate, $this->endDate, $this->locaties);
        $this->resultKlantenVerslagenTotalUniqueWOActief = $this->dao->getTotalUniqueKlantenEnGezinnenForLocaties($this->startDate,$this->endDate,$this->locaties);

        $this->resultAanmeldingen = $this->mdsDao->findAllAanmeldingenForLocaties($this->startDate,$this->endDate,$this->locaties);
        $this->resultBinnenVia = $this->mdsDao->findAllAanmeldingenBinnenVia($this->startDate,$this->endDate,$this->locaties);

        $this->resultAfsluitingen = $this->mdsDao->findAllAfsluitredenenAfgeslotenKlantenForLocaties($this->startDate,$this->endDate,$this->locaties);
        $this->resultDoorlooptijd = $this->mdsDao->findDoorlooptijdForLocaties($this->startDate,$this->endDate,$this->locaties);
    }

    protected function buildAantalKlantenVerslagenContactmomenten($data, $total, $titel, $columns = [])
    {
        $columns = [
            'Klanten' => 'aantalKlanten',
            'Aantal gezinnen' => 'aantalGezinnen',
            'Verslagen' => 'aantalVerslagen',
            'Aantal contactmomenten' => 'aantalContactmomenten',
            'Inloopverslagen' => 'aantalInloop',
            'MW verslagen' => 'aantalMw',
        ];

        return parent::buildAantalKlantenVerslagenContactmomenten($data, $total, $titel, $columns);
    }
}
