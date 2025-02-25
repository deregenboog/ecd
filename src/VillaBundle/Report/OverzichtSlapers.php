<?php

namespace VillaBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use VillaBundle\Service\SlaperDaoInterface;

class OverzichtSlapers extends AbstractReport
{
    protected $title = 'Overzicht slapers';
    protected $data = [];

    /**
     * @var SlaperDaoInterface
     */
    protected $dao;

    public function __construct(SlaperDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->data[''] = $this->dao->getSlapersWithOvernachtingen($this->startDate, $this->endDate);
    }

    protected function build()
    {
        foreach ($this->data as $title => $data) {
            $listing = new Listing($data, ['Naam' => 'naam', 'Aantal overnachtingen' => 'aantal','Type'=>'type']);
            $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => $title,
                'data' => $listing->render(),
            ];
        }
    }
}