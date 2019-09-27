<?php

namespace HsBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use HsBundle\Service\KlantDaoInterface;

class NieuweKlantenPerGgwGebied extends AbstractReport
{
    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    protected $title = 'Nieuwe klanten per GGW-gebied';

    protected $yPath = 'ggwgebied';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal klanten waarvan de inschrijfdatum binnen de opgegeven periode valt (incl. klanten zonder klus)';

    protected $yDescription = 'GGW-gebied (van klant)';

    protected $tables = [];

    public function __construct(KlantDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->data = $this->dao->countNewByGgwGebied($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $table = new Table($this->data, $this->xPath, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports[] = [
            'title' => $this->title,
            'xDescription' => $this->xDescription,
            'yDescription' => $this->yDescription,
            'data' => $table->render(),
        ];
    }
}
