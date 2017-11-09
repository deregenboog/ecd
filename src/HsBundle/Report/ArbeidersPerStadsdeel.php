<?php

namespace HsBundle\Report;

use AppBundle\Report\Table;
use HsBundle\Service\DienstverlenerDaoInterface;
use HsBundle\Service\VrijwilligerDaoInterface;

class ArbeidersPerStadsdeel extends AbstractReport
{
    /**
     * @var DienstverlenerDaoInterface
     */
    protected $dienstverlenerDao;

    /**
     * @var VrijwilligerDaoInterface
     */
    protected $vrijwilligerDao;

    protected $title = 'Dienstverleners en vrijwilligers per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal %ss actief binnen de opgegeven periode';

    protected $yDescription = 'Stadsdeel van %s';

    protected $data = [];

    public function __construct(
        DienstverlenerDaoInterface $dienstverlenerDao,
        VrijwilligerDaoInterface $vrijwilligerDao
    ) {
        $this->dienstverlenerDao = $dienstverlenerDao;
        $this->vrijwilligerDao = $vrijwilligerDao;
    }

    protected function init()
    {
        $this->data['dienstverlener'] = $this->dienstverlenerDao->countByStadsdeel($this->startDate, $this->endDate);
        $this->data['vrijwilliger'] = $this->vrijwilligerDao->countByStadsdeel($this->startDate, $this->endDate);
        $this->data['dienstverleners/vrijwilliger'] = array_merge($this->data['dienstverlener'], $this->data['vrijwilliger']);
    }

    protected function build()
    {
        foreach ($this->data as $type => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => ucfirst($type).'s',
                'xDescription' => sprintf($this->xDescription, $type),
                'yDescription' => sprintf($this->yDescription, $type),
                'data' => $table->render(),
            ];
        }

        $this->reports[count($this->reports) - 1]['title'] = 'Totaal';
    }
}
