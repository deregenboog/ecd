<?php

namespace IzBundle\Report;

use AppBundle\Exception\ReportException;
use AppBundle\Report\Table;
use IzBundle\Entity\Doelstelling;
use IzBundle\Entity\Project;
use IzBundle\Repository\DoelstellingRepository;
use IzBundle\Repository\HulpvraagRepository;
use IzBundle\Repository\ProjectRepository;

class HulpvraagsoortPerDoelgroepPerStadsdeelNieuw extends AbstractReport
{
    protected $title = 'Hulpvraagsoort per Doelgroep per Stadsdeel (Nieuw)';

    protected $xPath = 'hulpvraagsoortnaam';

    protected $yPath = 'doelgroepnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Hulpvraagsoort';

    protected $yDescription = 'Doelgroep';

    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    private $data = [];

    /**
     * @var Project[]
     */
    private $projecten;


    public function __construct(
        HulpvraagRepository $repository

    ) {
        $this->repository = $repository;

    }

    protected function init()
    {
        if ($this->startDate->format('Y') !== $this->endDate->format('Y')) {
            throw new ReportException('Startdatum en einddatum moeten binnen hetzelfde kalenderjaar liggen.');
        }

        $this->queue = new \SplPriorityQueue();
//        $this->initTotal();
        $this->initStadsdelen();

        foreach ($this->queue as $data) {
            $this->data = array_merge($this->data, $data);
        }
    }

    private function initTotal()
    {
        //        $stadsdelen["Centrum"] = $this->repository->countDoelgroepenPerHulpvraagsoortPerStadsdeel( $this->startDate, $this->endDate,"Centrum");
    }

    private function initStadsdelen()
    {

        $stadsdelenData = $this->repository->getStadsdelen($this->startDate, $this->endDate);

        $stadsdelen = array();
        foreach($stadsdelenData as $r)
        {
            if($r['stadsdeel'] === null){
                $r['stadsdeel'] = "Overig";
            }

            $stadsdelen[$r['stadsdeel']] = $this->repository->countDoelgroepenPerHulpvraagsoortPerStadsdeel( $this->startDate, $this->endDate,["type"=>"gestart","stadsdeel"=>$r['stadsdeel']]);
        }
        $stadsdelen["Alles"] = $this->repository->countDoelgroepenPerHulpvraagsoortPerStadsdeel( $this->startDate, $this->endDate,["type"=>"gestart","stadsdeel"=>"Alles"]);


        //fix overig...
        array_walk($stadsdelen['Overig'],function(&$val,$key){
            if($val['stadsdeel'] == null) $val['stadsdeel'] = 'Overig';
        });




//        $stadsdelen = array_unique(array_column($data, 'stadsdeel'));
        $priority = 40;
        foreach ($stadsdelen as $stadsdeel=>$data) {
            $this->queue->insert(
                ['Stadsdeel '.$stadsdeel => array_filter($data, function ($item) use ($stadsdeel) {
                    return $item['stadsdeel'] === $stadsdeel;
                })],
                --$priority
            );
        }
    }


    protected function build()
    {
        foreach ($this->data as $title => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $table->setXSort(false)->setXTotals(false);
            $data = $table->render();

//            foreach ($data as &$row) {
//                if (!isset($row['Prestatie'])) {
//                    $row['Prestatie'] = 0;
//                }
//                if (!isset($row['Doelstelling'])) {
//                    $row['Doelstelling'] = 0;
//                }
//                if (0 == $row['Doelstelling']) {
//                    $row['Behaald percentage'] = 0;
//                } else {
//                    $row['Behaald percentage'] = 100 * round($row['Prestatie'] / $row['Doelstelling'], 2);
//                }
//            }



            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $data,
            ];
        }
    }
}
