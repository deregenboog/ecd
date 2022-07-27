<?php

namespace DagbestedingBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use DagbestedingBundle\Service\DeelnemerDaoInterface;

class DeelnemersZonderToestemmingsformulier extends AbstractReport
{
    protected $title = 'Deelnemers zonder Toestemmingsformulier';

    protected $xPath = '';

    protected $yPath = 'naam';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Project';

    protected $tables = [];

    public function __construct(DeelnemerDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $data = $this->dao->deelnemersZonderToestemmingsformulier(DeelnemerDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);


        $listing = new Listing($data, ['Deelnemer nummer'=>'id','Naam' => 'naam','Project(en)'=>'projectNaam']);
        $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => "Deelnemers zonder VOG",
                'data' => $listing->render(),
            ];

    }
}
