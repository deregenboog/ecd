<?php

namespace HsBundle\Report;

use AppBundle\Report\Table;
use HsBundle\Entity\OekDeelnameStatus;
use HsBundle\Repository\OekKlantRepository;
use HsBundle\Service\KlantDaoInterface;
use HsBundle\Service\DienstverlenerDaoInterface;
use HsBundle\Service\VrijwilligerDaoInterface;
use HsBundle\Service\KlusDaoInterface;

class KoppelingenPerStadsdeel extends AbstractReport
{
    /**
     * @var KlusDaoInterface
     */
    protected $dao;

    protected $title = 'Koppelingen per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen tussen %ss en klanten waarbij de startdatum van de klus binnen de opgegeven periode valt';

    protected $yDescription = 'Stadsdeel (van %s)';

    protected $data = [];

    public function __construct(KlusDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->data['dienstverlener'] = $this->dao->countDienstverlenersByStadsdeel($this->startDate, $this->endDate);
        $this->data['vrijwilliger'] = $this->dao->countVrijwilligersByStadsdeel($this->startDate, $this->endDate);
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
