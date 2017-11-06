<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;
use IzBundle\Repository\IzHulpvraagRepository;
use IzBundle\Repository\DoelstellingRepository;
use Doctrine\ORM\EntityManager;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Postcode;
use Doctrine\ORM\AbstractQuery;
use AppBundle\Exception\ReportException;
use IzBundle\Repository\IzProjectRepository;

class Managementrapportage extends AbstractReport
{
    protected $title = 'Managementrapportage';

    protected $xPath = 'kolom';

    protected $yPath = 'project';

    protected $nPath = 'aantal';

    protected $xDescription = 'Koppelingen';

    protected $yDescription = 'Project';

    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    private $data = [];

    /**
     * @var IzProject[]
     */
    private $projecten;

    /**
     * @var array
     */
    private $teams = [
        'Team C O N' => ['Centrum', 'Oost', 'Noord'],
        'Team ZO Z Diemen' => ['Zuidoost', 'Zuid', 'Diemen'],
        'Team W NW' => ['West', 'Nieuw-West'],
    ];

    public function __construct(
        IzHulpvraagRepository $repository,
        DoelstellingRepository $doelstellingRepository,
        IzProjectRepository $projectRepository
    ) {
        $this->repository = $repository;
        $this->doelstellingRepository = $doelstellingRepository;
        $this->projecten = $projectRepository->findActive();
    }

    protected function init()
    {
        if ($this->startDate->format('Y') !== $this->endDate->format('Y')) {
            throw new ReportException('Startdatum en einddatum moeten binnen hetzelfde kalenderjaar liggen.');
        }

        $this->queue = new \SplPriorityQueue();
        $this->initTotal();
        $this->initStadsdelen();
        $this->initTeams();
        foreach ($this->queue as $data) {
            $this->data = array_merge($this->data, $data);
        }
    }

    private function initTotal()
    {
        $beginstand = $this->repository->countKoppelingenByProject('beginstand', $this->startDate, $this->endDate);
        array_walk($beginstand, function(&$item) {
            $item['kolom'] = 'Caseload '.$this->startDate->format('d-m-Y');
        });

        $gestart = $this->repository->countKoppelingenByProject('gestart', $this->startDate, $this->endDate);
        array_walk($gestart, function(&$item) {
            $item['kolom'] = 'Gestart';
        });

        $eindstand = $this->repository->countKoppelingenByProject('eindstand', $this->startDate, $this->endDate);
        array_walk($eindstand, function(&$item) {
            $item['kolom'] = 'Caseload '.$this->endDate->format('d-m-Y');
        });

        $prestaties = $gestart;
        foreach ($this->projecten as $project) {
            if ($project->getPrestatieStrategy() === IzProject::STRATEGY_PRESTATIE_TOTAL) {
                $prestaties = array_merge($prestaties, array_filter($beginstand, function($row) use ($project) {
                    return $row['project'] === $project->getNaam();
                }));
            }
        }
        array_walk($prestaties, function(&$item) {
            $item['kolom'] = 'Prestatie';
        });

        $doelstellingen = $this->doelstellingRepository->countByJaar($this->startDate->format('Y'));
        array_walk($doelstellingen, function(&$item) {
            $item['kolom'] = 'Doelstelling';
        });

        $this->queue->insert(
            ['Totaal' => array_merge($beginstand, $gestart, $eindstand, $prestaties, $doelstellingen)],
            100
        );
    }

    private function initStadsdelen()
    {
        $beginstand = $this->repository->countKoppelingenByProjectAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        array_walk($beginstand, function(&$item) {
            $item['kolom'] = 'Caseload '.$this->startDate->format('d-m-Y');
        });

        $gestart = $this->repository->countKoppelingenByProjectAndStadsdeel('gestart', $this->startDate, $this->endDate);
        array_walk($gestart, function(&$item) {
            $item['kolom'] = 'Gestart';
        });

        $eindstand = $this->repository->countKoppelingenByProjectAndStadsdeel('eindstand', $this->startDate, $this->endDate);
        array_walk($eindstand, function(&$item) {
            $item['kolom'] = 'Caseload '.$this->endDate->format('d-m-Y');
        });

        $prestaties = $gestart;
        foreach ($this->projecten as $project) {
            if ($project->getPrestatieStrategy() === IzProject::STRATEGY_PRESTATIE_TOTAL) {
                $prestaties = array_merge($prestaties, array_filter($beginstand, function($row) use ($project) {
                    return $row['project'] === $project->getNaam();
                }));
            }
        }
        array_walk($prestaties, function(&$item) {
            $item['kolom'] = 'Prestatie';
        });

        $doelstellingen = $this->doelstellingRepository->countByJaarAndProjectAndStadsdeel($this->startDate->format('Y'));
        array_walk($doelstellingen, function(&$item) {
            $item['kolom'] = 'Doelstelling';
        });

        // merge data
        $data = array_merge($beginstand, $gestart, $eindstand, $doelstellingen, $prestaties);

        // fix missing "stadsdelen"
        array_walk($data, function(&$item) {
            if (!$item['stadsdeel']) {
                $item['stadsdeel'] = 'Overig';
            }
        });

        $stadsdelen = array_unique(array_column($data, 'stadsdeel'));
        $priority = 40;
        foreach ($stadsdelen as $stadsdeel) {
            $this->queue->insert(
                ['Stadsdeel '.$stadsdeel => array_filter($data, function($item) use ($stadsdeel) {
                    return $item['stadsdeel'] === $stadsdeel;
                })],
                --$priority
            );
        }
    }

    private function initTeams()
    {
        // re-use existing data
        $existingData = [];
        foreach (clone $this->queue as $data) {
            $existingData = array_merge($existingData, $data);
        }

        // init structure
        foreach (array_keys($this->teams) as $team) {
            $teamData[$team] = [];
        }

        // cijfers centrale stad verdelen over teams
        $doelstellingen = $this->doelstellingRepository->countByJaarWithoutStadsdeel($this->startDate->format('Y'));
        foreach ($doelstellingen as &$doelstelling) {
            $remainingTeams = count($this->teams);
            foreach (array_keys($this->teams) as $team) {
                $amount = ceil($doelstelling['aantal'] / $remainingTeams);
                $doelstelling['aantal'] -= $amount;
                $teamData[$team][] = [
                    'project' => $doelstelling['project'],
                    'kolom' => 'Doelstelling',
                    'aantal' => $amount,
                ];
                --$remainingTeams;
            }
        }

        // cijfers stadsdelen toekennen aan betreffende teams
        foreach ($this->teams as $team => $stadsdelen) {
            foreach ($stadsdelen as $stadsdeel) {
                if (array_key_exists('Stadsdeel '.$stadsdeel, $existingData)) {
                    $teamData[$team] = array_merge($teamData[$team], $existingData['Stadsdeel '.$stadsdeel]);
                }
            }
        }

        foreach ($teamData as $key => $data) {
            $this->queue->insert(
                [$key => $data],
                50
            );
        }
    }

    protected function build()
    {
        foreach ($this->data as $title => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $table->setXSort(false)->setXTotals(false);
            $data = $table->render();

            foreach ($data as &$row) {
                if (!isset($row['Prestatie'])) {
                    $row['Prestatie'] = 0;
                }
                if (!isset($row['Doelstelling'])) {
                    $row['Doelstelling'] = 0;
                }
                if (0 === $row['Doelstelling']) {
                    $row['Behaald percentage'] = 0;
                } else {
                    $row['Behaald percentage'] = 100 * round($row['Prestatie'] / $row['Doelstelling'], 2);
                }
            }

            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $data,
            ];
        }
    }
}
