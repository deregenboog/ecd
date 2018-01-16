<?php

namespace IzBundle\Report;

use AppBundle\Report\Listing;

abstract class AbstractVrijwilligersNamenlijstReport extends AbstractVrijwilligersReport
{
    protected $columns = [
        'Nummer' => 'id',
        'Naam' => 'naam',
        'Aantal hulpaanbiedingen' => 'hulpaanbiedingen',
    ];

    protected function build()
    {
        $beginstandListing = new Listing($this->beginstand, $this->columns);
        $beginstandListing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $gestartListing = new Listing($this->gestart, $this->columns);
        $gestartListing->setStartDate($this->startDate)->setEndDate($this->endDate);
        
        $nieuwGestartListing = new Listing($this->nieuwGestart, $this->columns);
        $nieuwGestartListing->setStartDate($this->startDate)->setEndDate($this->endDate);
        
        $afgeslotenListing = new Listing($this->afgesloten, $this->columns);
        $afgeslotenListing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $eindstandListing = new Listing($this->eindstand, $this->columns);
        $eindstandListing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Beginstand',
                'xDescription' => 'Vrijwilligers met een lopende koppeling op startdatum.',
                'yDescription' => $this->yDescription,
                'data' => $beginstandListing->render(),
            ],
            [
                'title' => 'Gestart',
                'xDescription' => 'Vrijwilligers dat binnen de periode een koppeling startte en op startdatum geen lopende koppeling had.',
                'yDescription' => $this->yDescription,
                'data' => $gestartListing->render(),
            ],
            [
                'title' => 'Nieuw gestart',
                'xDescription' => 'Vrijwilligers dat binnen de periode voor het eerst een koppeling startte.',
                'yDescription' => $this->yDescription,
                'data' => $nieuwGestartListing->render(),
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => 'Vrijwilligers dat binnen de periode een koppeling afsloot en op einddatum geen lopende koppeling had.',
                'yDescription' => $this->yDescription,
                'data' => $afgeslotenListing->render(),
            ],
            [
                'title' => 'Eindstand',
                'xDescription' => 'Vrijwilligers met een lopende koppeling op einddatum.',
                'yDescription' => $this->yDescription,
                'data' => $eindstandListing->render(),
            ],
        ];
    }
}
