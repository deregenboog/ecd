<?php

namespace AppBundle\Report;

use AppBundle\Entity\ZrmV1;
use Doctrine\ORM\EntityManager;

class ZrmScorePerPostcodegebied extends AbstractReport
{
    protected $title = 'ZRM-score per postcodegbied';

    protected $xPath = 'leefgebied';

    protected $yPath = 'postcodegebied';

    protected $nPath = 'aantal';

    protected $xDescription = 'Leefgebied';

    protected $yDescription = 'Postcodegebied';

    protected $tables = [];

    protected $class = ZrmV1::class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function init()
    {
        $class = $this->class;
        $leefgebieden = $class::getFieldsAndLabels();

        foreach (range(1, 5) as $score) {
            $data = [];
            foreach ($leefgebieden as $leefgebied => $title) {
                $result = $this->em->createQueryBuilder()
                    ->select("postcodegebied.naam AS groep, COUNT(zrm.{$leefgebied}) AS aantal")
                    ->from($this->class, 'zrm')
                    ->innerJoin('zrm.klant', 'klant')
                    ->leftJoin('klant.postcodegebied', 'postcodegebied')
                    ->where("zrm.{$leefgebied} = {$score}")
                    ->andWhere('DATE(zrm.created) BETWEEN :start AND :end')
                    ->groupBy('groep')
                    ->orderBy('postcodegebied.naam')
                    ->setParameters([
                        'start' => $this->startDate,
                        'end' => $this->endDate,
                    ])
                    ->getQuery()
                    ->getResult();

                foreach ($result as $item) {
                    $data[] = [
                        'leefgebied' => $title,
                        'postcodegebied' => $item['groep'],
                        'aantal' => $item['aantal'],
                    ];
                }
            }
            $this->tables['Score '.$score] = $data;
        }
    }

    protected function build()
    {
        foreach ($this->tables as $title => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate)->setXTotals(false);
            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
