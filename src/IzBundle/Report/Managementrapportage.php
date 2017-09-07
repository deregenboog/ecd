<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;
use IzBundle\Repository\IzHulpvraagRepository;

class Managementrapportage extends AbstractReport
{
    protected $title = 'Managementrapportage';

    protected $xPath = 'status';

    protected $yPath = 'project';

    protected $nPath = 'aantal';

    protected $xDescription = 'Koppelingen';

    protected $yDescription = 'Project';

    private $data = [];

    public function __construct(IzHulpvraagRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByProjectAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByProjectAndStadsdeel('gestart', $this->startDate, $this->endDate);

        $teams = [
            'Team CON' => ['Centrum', 'Oost', 'Noord'],
            'Team ZO Z Diemen' => ['Zuidoost', 'Zuid', 'Diemen'],
            'Team W NW' => ['West', 'Nieuw-West'],
            'Amstelveen' => ['Amstelveen'],
        ];

        foreach ($teams as $team => $stadsdelen) {
            $status = 'Beginstand';
            foreach ($this->beginstand as $i => $item) {
                if (in_array($item['stadsdeel'], $stadsdelen)) {
                    $this->data[$team][] = [
                        'project' => $item['project'],
                        'aantal' => $item['aantal'],
                        'status' => $status,
                    ];
                    unset($this->beginstand[$i]);
                }
            }
            $status = 'Gestart';
            foreach ($this->gestart as $i => $item) {
                if (in_array($item['stadsdeel'], $stadsdelen)) {
                    $this->data[$team][] = [
                        'project' => $item['project'],
                        'aantal' => $item['aantal'],
                        'status' => $status,
                    ];
                    unset($this->gestart[$i]);
                }
            }
        }

        $team = 'Overig';
        $status = 'Beginstand';
        foreach ($this->beginstand as $item) {
            $this->data[$team][] = [
                'project' => $item['project'],
                'aantal' => $item['aantal'],
                'status' => $status,
            ];
        }
        $status = 'Gestart';
        foreach ($this->gestart as $item) {
            $this->data[$team][] = [
                'project' => $item['project'],
                'aantal' => $item['aantal'],
                'status' => $status,
            ];
        }
    }

    protected function build()
    {
        foreach ($this->data as $title => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
