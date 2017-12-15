<?php

namespace ClipBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use ClipBundle\Service\ContactmomentDaoInterface;
use ClipBundle\Service\VraagDaoInterface;

class VragenPerMaand extends AbstractReport
{
    protected $title = 'Vragen per maand';

    protected $xPath = 'kolom';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Maand';

    protected $tables = [];

    /**
     * @var VraagDaoInterface
     */
    private $vraagDao;

    /**
     * @var ContactmomentDaoInterface
     */
    private $contactmomentDao;

    public function __construct(VraagDaoInterface $vraagDao, ContactmomentDaoInterface $contactmomentDao)
    {
        $this->vraagDao = $vraagDao;
        $this->contactmomentDao = $contactmomentDao;
    }

    protected function init()
    {
        $vragen = $this->vraagDao->countByMaand($this->startDate, $this->endDate);
        array_walk($vragen, function (&$item) {
            $item['kolom'] = 'Vragen';
        });

        $contactmomenten = $this->contactmomentDao->countByMaand($this->startDate, $this->endDate);
        array_walk($contactmomenten, function (&$item) {
            $item['kolom'] = 'Contactmomenten';
        });

        $this->tables[''] = array_merge($vragen, $contactmomenten);
    }

    protected function build()
    {
        foreach ($this->tables as $title => $table) {
            $table = new Table($table, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);
            $table->setXSort(false)->setYSort(false)->setXTotals(false);

            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
