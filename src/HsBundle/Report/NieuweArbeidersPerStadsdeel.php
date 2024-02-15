<?php

namespace HsBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use HsBundle\Service\DienstverlenerDaoInterface;
use HsBundle\Service\KlantDaoInterface;
use HsBundle\Service\VrijwilligerDaoInterface;

class NieuweArbeidersPerStadsdeel extends AbstractReport
{
    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    protected $title = 'Nieuwe dienstverleners en vrijwilligers per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal %ss waarvan de inschrijfdatum binnen de opgegeven periode valt (incl. %s zonder klus)';

    protected $yDescription = 'Stadsdeel van %s';

    protected $tables = [];

    protected $data = [];

    private DienstverlenerDaoInterface $dienstverlenerDao;

    private VrijwilligerDaoInterface $vrijwilligerDao;

    public function __construct(
        DienstverlenerDaoInterface $dienstverlenerDao,
        VrijwilligerDaoInterface $vrijwilligerDao
    ) {
        $this->dienstverlenerDao = $dienstverlenerDao;
        $this->vrijwilligerDao = $vrijwilligerDao;
    }

    protected function init()
    {
        $this->data['dienstverlener'] = $this->dienstverlenerDao->countNewByStadsdeel($this->startDate, $this->endDate);
        $this->data['vrijwilliger'] = $this->vrijwilligerDao->countNewByStadsdeel($this->startDate, $this->endDate);
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
