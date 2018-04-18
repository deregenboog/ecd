<?php

namespace IzBundle\Report;

use AppBundle\Exception\ReportException;
use AppBundle\Report\Table;
use IzBundle\Entity\Doelstelling;
use IzBundle\Entity\Project;
use IzBundle\Repository\DoelstellingRepository;
use IzBundle\Repository\HulpvraagRepository;
use IzBundle\Repository\ProjectRepository;

class Managementrapportage extends AbstractReport
{
    protected $title = 'Managementrapportage';

    protected $xPath = 'kolom';

    protected $yPath = 'projectnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Koppelingen';

    protected $yDescription = 'Project';

    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    private $data = [];

    /**
     * @var Project[]
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
        HulpvraagRepository $repository,
        DoelstellingRepository $doelstellingRepository,
        ProjectRepository $projectRepository
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
        $this->initFondsen();
        foreach ($this->queue as $data) {
            $this->data = array_merge($this->data, $data);
        }
    }

    private function initTotal()
    {
        $beginstand = $this->repository->countKoppelingenByProject('beginstand', $this->startDate, $this->endDate);
        array_walk($beginstand, function (&$item) {
            $item['kolom'] = 'Caseload startdatum';
        });

        $gestart = $this->repository->countKoppelingenByProject('gestart', $this->startDate, $this->endDate);
        array_walk($gestart, function (&$item) {
            $item['kolom'] = 'Gestart';
        });

        $eindstand = $this->repository->countKoppelingenByProject('eindstand', $this->startDate, $this->endDate);
        array_walk($eindstand, function (&$item) {
            $item['kolom'] = 'Caseload einddatum';
        });

        if ($this->startDate->format('Y') <= 2017) {
            $prestaties = $gestart;
            foreach ($this->projecten as $project) {
                if (Project::STRATEGY_PRESTATIE_TOTAL === $project->getPrestatieStrategy()) {
                    $prestaties = array_merge($prestaties, array_filter($beginstand, function ($row) use ($project) {
                        return $row['projectnaam'] === $project->getNaam();
                    }));
                }
            }
        } elseif (2018 == $this->startDate->format('Y')) {
            $prestaties = array_merge($beginstand, $gestart);
        } else {
            $prestaties = $gestart;
        }
        array_walk($prestaties, function (&$item) {
            $item['kolom'] = 'Prestatie';
        });

        $doelstellingen = $this->doelstellingRepository->countByJaar($this->startDate->format('Y'));
        array_walk($doelstellingen, function (&$item) {
            $item['kolom'] = 'Doelstelling';
        });

        $data = array_merge($beginstand, $gestart, $eindstand, $prestaties, $doelstellingen);
        $this->queue->insert(['Totaal' => $data], 100);
    }

    private function initStadsdelen()
    {
        $beginstand = $this->repository->countKoppelingenByProjectAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        array_walk($beginstand, function (&$item) {
            $item['kolom'] = 'Caseload startdatum';
        });

        $gestart = $this->repository->countKoppelingenByProjectAndStadsdeel('gestart', $this->startDate, $this->endDate);
        array_walk($gestart, function (&$item) {
            $item['kolom'] = 'Gestart';
        });

        $eindstand = $this->repository->countKoppelingenByProjectAndStadsdeel('eindstand', $this->startDate, $this->endDate);
        array_walk($eindstand, function (&$item) {
            $item['kolom'] = 'Caseload einddatum';
        });

        if ($this->startDate->format('Y') <= 2017) {
            $prestaties = $gestart;
            foreach ($this->projecten as $project) {
                if (Project::STRATEGY_PRESTATIE_TOTAL === $project->getPrestatieStrategy()) {
                    $prestaties = array_merge($prestaties, array_filter($beginstand, function ($row) use ($project) {
                        return $row['projectnaam'] === $project->getNaam();
                    }));
                }
            }
        } elseif (2018 == $this->startDate->format('Y')) {
            $prestaties = array_merge($beginstand, $gestart);
        } else {
            $prestaties = $gestart;
        }
        array_walk($prestaties, function (&$item) {
            $item['kolom'] = 'Prestatie';
        });

        $doelstellingen = $this->doelstellingRepository->countByJaarAndProjectAndStadsdeel($this->startDate->format('Y'));
        array_walk($doelstellingen, function (&$item) {
            $item['kolom'] = 'Doelstelling';
        });

        // merge data
        $data = array_merge($beginstand, $gestart, $eindstand, $prestaties, $doelstellingen);

        // fix missing "stadsdelen"
        array_walk($data, function (&$item) {
            if (!$item['stadsdeel']) {
                $item['stadsdeel'] = 'Overig';
            }
        });

        $stadsdelen = array_unique(array_column($data, 'stadsdeel'));
        $priority = 40;
        foreach ($stadsdelen as $stadsdeel) {
            $this->queue->insert(
                ['Stadsdeel '.$stadsdeel => array_filter($data, function ($item) use ($stadsdeel) {
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

        // cijfers stadsdelen toekennen aan betreffende teams
        foreach ($this->teams as $team => $stadsdelen) {
            foreach ($stadsdelen as $stadsdeel) {
                if (array_key_exists('Stadsdeel '.$stadsdeel, $existingData)) {
                    $teamData[$team] = array_merge($teamData[$team], $existingData['Stadsdeel '.$stadsdeel]);
                }
            }
        }

        // cijfers zonder stadsdeel verdelen over teams
        $doelstellingen = $this->doelstellingRepository->countByJaarWithoutStadsdeel($this->startDate->format('Y'));
        foreach ($doelstellingen as &$doelstelling) {
            $remainingTeams = count($this->teams);
            foreach (array_keys($this->teams) as $team) {
                $amount = round($doelstelling['aantal'] / $remainingTeams);
                $doelstelling['aantal'] -= $amount;
                $teamData[$team][] = [
                    'projectnaam' => $doelstelling['projectnaam'],
                    'kolom' => 'Doelstelling',
                    'aantal' => $amount,
                ];
                --$remainingTeams;
            }
        }

        foreach ($teamData as $key => $data) {
            $this->queue->insert([$key => $data], 50);
        }
    }

    private function initFondsen()
    {
        $beginstand = $this->repository->countKoppelingenByProject('beginstand', $this->startDate, $this->endDate);
        array_walk($beginstand, function (&$item) {
            $item['kolom'] = 'Caseload startdatum';
        });

        $gestart = $this->repository->countKoppelingenByProject('gestart', $this->startDate, $this->endDate);
        array_walk($gestart, function (&$item) {
            $item['kolom'] = 'Gestart';
        });

        $eindstand = $this->repository->countKoppelingenByProject('eindstand', $this->startDate, $this->endDate);
        array_walk($eindstand, function (&$item) {
            $item['kolom'] = 'Caseload einddatum';
        });

        if ($this->startDate->format('Y') <= 2017) {
            $prestaties = $gestart;
            foreach ($this->projecten as $project) {
                if (Project::STRATEGY_PRESTATIE_TOTAL === $project->getPrestatieStrategy()) {
                    $prestaties = array_merge($prestaties, array_filter($beginstand, function ($row) use ($project) {
                        return $row['projectnaam'] === $project->getNaam();
                    }));
                }
            }
        } elseif (2018 == $this->startDate->format('Y')) {
            $prestaties = array_merge($beginstand, $gestart);
        } else {
            $prestaties = $gestart;
        }
        array_walk($prestaties, function (&$item) {
            $item['kolom'] = 'Prestatie';
        });

        $doelstellingen = $this->doelstellingRepository->countByJaarAndProjectAndCategorie($this->startDate->format('Y'));
        $doelstellingen = array_filter($doelstellingen, function ($doelstelling) {
            return Doelstelling::CATEGORIE_FONDSEN === $doelstelling['categorie'];
        });
        array_walk($doelstellingen, function (&$item) {
            $item['kolom'] = 'Doelstelling';
        });

        $data = array_merge($beginstand, $gestart, $eindstand, $prestaties, $doelstellingen);
        $this->queue->insert(['Fondsen' => $data], 0);
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
                if (0 == $row['Doelstelling']) {
                    $row['Behaald percentage'] = 0;
                } else {
                    $row['Behaald percentage'] = 100 * round($row['Prestatie'] / $row['Doelstelling'], 2);
                }
            }

            if ('Fondsen' === $title) {
                // don't show rows without target
                $data = array_filter($data, function ($row) {
                    return $row['Doelstelling'] > 0;
                });
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
