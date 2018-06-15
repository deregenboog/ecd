<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use InloopBundle\Entity\Locatie;
use MwBundle\Service\InventarisatieDaoInterface;

class Inventarisaties extends AbstractReport
{
    protected $title = 'Inventarisaties';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $yDescription = 'Inventarisatie';

    protected $tables = [];

    /**
     * @var Locatie
     */
    private $locatie;

    public function __construct(InventarisatieDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    public function setFilter(array $filter)
    {
        if (array_key_exists('startdatum', $filter)) {
            $this->startDate = $filter['startdatum'];
        }

        if (array_key_exists('startdatum', $filter)) {
            $this->endDate = $filter['einddatum'];
        }

        if (array_key_exists('locatie', $filter)) {
            $this->locatie = $filter['locatie'];
        }

        return $this;
    }

    protected function init()
    {
        $inventarisaties = $this->dao->countInventarisaties(
            $this->startDate,
            $this->endDate,
            $this->locatie
        );

        foreach ($inventarisaties as $inventarisatie) {
            $titels = [];
            foreach ($inventarisatie['path'] as $i => $node) {
                if (0 === $i) {
                    $root = $node;
                    $data = [];
                }
                $titels[] = $node->getTitel();
                if (!$node->hasChildren()) {
                    $this->tables[$root->getTitel()][] = [
                        'groep' => implode(' => ', $titels),
                        'aantal' => $inventarisatie['aantal_verslagen'],
                    ];
                }
            }
        }
    }

    protected function build()
    {
        foreach ($this->tables as $title => $table) {
            $table = new Grid($table, ['Aantal' => 'aantal'], $this->yPath);
            $table
                ->setStartDate($this->startDate)
                ->setEndDate($this->endDate)
                ->setYSort(false)
                ->setYTotals(false)
            ;

            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
