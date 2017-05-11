<?php

namespace HsBundle\Report;

use AppBundle\Report\Table;
use HsBundle\Entity\OekDeelnameStatus;
use HsBundle\Repository\OekKlantRepository;
use HsBundle\Service\KlantDaoInterface;
use HsBundle\Service\VrijwilligerDaoInterface;
use HsBundle\Service\DienstverlenerDaoInterface;

class NieuweArbeidersPerStadsdeel extends AbstractReport
{
    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    protected $title = 'Nieuwe dienstverleners en vrijwilligers per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal unieke %ss (met of zonder klus) waarvan de inschrijfdatum binnen de opgegeven periode valt';

    protected $yDescription = 'Stadsdeel (van %s)';

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
        $this->data['dienstverlener'] = $this->dienstverlenerDao->countNewByStadsdeel($this->startDate, $this->endDate);
        $this->data['vrijwilliger'] = $this->vrijwilligerDao->countNewByStadsdeel($this->startDate, $this->endDate);
    }

    protected function build()
    {
        foreach ($this->data as $type => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => ucfirst($type) . 's',
                'xDescription' => sprintf($this->xDescription, $type),
                'yDescription' => sprintf($this->yDescription, $type),
                'data' => $table->render(),
            ];
        }
    }
}
