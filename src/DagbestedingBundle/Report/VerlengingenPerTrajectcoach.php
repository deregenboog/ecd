<?php

namespace DagbestedingBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use DagbestedingBundle\Service\TrajectDaoInterface;

class VerlengingenPerTrajectcoach extends AbstractReport
{
    protected $title = 'Verlengingen per trajectcoach';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Trajectcoach';

    protected $tables = [];

    public function __construct(TrajectDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $data = $this->dao->getVerlengingenPerTrajectcoach($this->startDate, $this->endDate);

        $listing = new Listing($data, ['Trajectcoach' => 'trajectCoach', 'Naam' => 'naam', 'Einddatum' => 'einddatum']);
        $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports[] = [
            'title' => 'Verlengingen per trajectcoach',
            'data' => $listing->render(),
        ];
    }
}
