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

        $afgeslotenListing = new Listing($this->afgesloten, $this->columns);
        $afgeslotenListing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $eindstandListing = new Listing($this->eindstand, $this->columns);
        $eindstandListing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Beginstand',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $beginstandListing->render(),
            ],
            [
                'title' => 'Gestart',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $gestartListing->render(),
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $afgeslotenListing->render(),
            ],
            [
                'title' => 'Eindstand',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $eindstandListing->render(),
            ],
        ];
    }
}
