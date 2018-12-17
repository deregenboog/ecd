<?php

namespace HsBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use HsBundle\Service\KlantDaoInterface;

class KlantenPerGgwGebied extends AbstractReport
{
    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    protected $title = 'Klanten per GGW-gebied';

    protected $yPath = 'ggwgebied';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal klanten met klus waarvan de startdatum binnen de opgegeven periode valt';

    protected $yDescription = 'GGW-gebied van klant';

    protected $data = [];

    public function __construct(KlantDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->data = $this->dao->countByGgwGebied($this->startDate, $this->endDate);
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
