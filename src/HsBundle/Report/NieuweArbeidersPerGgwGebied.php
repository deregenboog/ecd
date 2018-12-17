<?php

namespace HsBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use HsBundle\Service\DienstverlenerDaoInterface;
use HsBundle\Service\KlantDaoInterface;
use HsBundle\Service\VrijwilligerDaoInterface;

class NieuweArbeidersPerGgwGebied extends AbstractReport
{
    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    protected $title = 'Nieuwe dienstverleners en vrijwilligers per GGW-gebied';

    protected $yPath = 'ggwgebied';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal %ss waarvan de inschrijfdatum binnen de opgegeven periode valt (incl. %s zonder klus)';

    protected $yDescription = 'GGW-gebied van %s';

    protected $tables = [];

    public function __construct(
        DienstverlenerDaoInterface $dienstverlenerDao,
        VrijwilligerDaoInterface $vrijwilligerDao
    ) {
        $this->dienstverlenerDao = $dienstverlenerDao;
        $this->vrijwilligerDao = $vrijwilligerDao;
    }

    protected function init()
    {
        $this->data['dienstverlener'] = $this->dienstverlenerDao->countNewByGgwGebied($this->startDate, $this->endDate);
        $this->data['vrijwilliger'] = $this->vrijwilligerDao->countNewByGgwGebied($this->startDate, $this->endDate);
        $this->data['dienstverleners/vrijwilliger'] = array_merge($this->data['dienstverlener'], $this->data['vrijwilliger']);
    }

    protected function build()
    {
        foreach ($this->data as $type => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => ucfirst($type).'s',
                'xDescription' => sprintf($this->xDescription, $type, $type),
                'yDescription' => sprintf($this->yDescription, $type),
                'data' => $table->render(),
            ];
        }

        $this->reports[count($this->reports) - 1]['title'] = 'Totaal';
    }
}
