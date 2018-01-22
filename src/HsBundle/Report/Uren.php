<?php

namespace HsBundle\Report;

use AppBundle\Report\Table;
use HsBundle\Service\KlantDaoInterface;
use AppBundle\Report\AbstractReport;
use HsBundle\Service\RegistratieDaoInterface;

class Uren extends AbstractReport
{
    /**
     * @var RegistratieDaoInterface
     */
    protected $dao;

    protected $title = 'Uren';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal gewerkte uren binnen de opgegeven periode';

    protected $data = [];

    public function __construct(RegistratieDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->urenPerStadsdeel = $this->dao->countUrenByStadsdeel($this->startDate, $this->endDate);
        $this->urenPerActiviteit = $this->dao->countUrenByActiviteit($this->startDate, $this->endDate);
        $this->urenPerKlant = $this->dao->countUrenByKlant($this->startDate, $this->endDate);
        $this->urenPerKlus = $this->dao->countUrenByKlus($this->startDate, $this->endDate);
        $this->urenPerDienstverlener = $this->dao->countUrenByDienstverlener($this->startDate, $this->endDate);
        $this->urenPerVrijwilliger = $this->dao->countUrenByVrijwilliger($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $table = new Table($this->urenPerStadsdeel, null, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);
        $this->reports[] = [
            'title' => 'Uren per stadsdeel',
            'xDescription' => $this->xDescription,
            'yDescription' => 'Stadsdeel van klant',
            'data' => $table->render(),
        ];

        $table = new Table($this->urenPerActiviteit, null, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);
        $this->reports[] = [
            'title' => 'Uren per activiteit',
            'xDescription' => $this->xDescription,
            'yDescription' => 'Activiteit',
            'data' => $table->render(),
        ];

        $table = new Table($this->urenPerKlant, null, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);
        $this->reports[] = [
            'title' => 'Uren per klant',
            'xDescription' => $this->xDescription,
            'yDescription' => 'Klant',
            'data' => $table->render(),
        ];

        $table = new Table($this->urenPerKlus, 'activiteitnaam', 'klusnaam', $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);
        $this->reports[] = [
            'title' => 'Uren per klus',
            'xDescription' => $this->xDescription,
            'yDescription' => 'Klus',
            'data' => $table->render(),
        ];

        $table = new Table($this->urenPerDienstverlener, null, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);
        $this->reports[] = [
            'title' => 'Uren per dienstverlener',
            'xDescription' => $this->xDescription,
            'yDescription' => 'Dienstverlener',
            'data' => $table->render(),
        ];

        $table = new Table($this->urenPerVrijwilliger, null, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);
        $this->reports[] = [
            'title' => 'Uren per vrijwilliger',
            'xDescription' => $this->xDescription,
            'yDescription' => 'Vrijwilliger',
            'data' => $table->render(),
        ];
    }
}
