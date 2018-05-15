<?php

namespace AppBundle\Report;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\ZrmV1;

class ZrmScorePerPostcodegebied extends AbstractReport
{
    protected $title = 'ZRM-score per postcodegbied';

    protected $xPath = 'leefgebied';

    protected $yPath = 'postcodegebied';

    protected $nPath = 'aantal';

    protected $xDescription = 'Leefgebied';

    protected $yDescription = 'Postcodegebied';

    protected $tables = [];

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function init()
    {
        $leefgebieden = [
            'Inkomen' => 'inkomen',
            'Dagbesteding' => 'dagbesteding',
            'Huisvesting' => 'huisvesting',
            'Gezinsrelaties' => 'gezinsrelaties',
            'Geestelijke gezondheid' => 'geestelijkeGezondheid',
            'Fysieke gezondheid' => 'fysiekeGezondheid',
            'Verslaving' => 'verslaving',
            'ADL-vaardigheden' => 'adlVaardigheden',
            'Sociaal netwerk' => 'sociaalNetwerk',
            'Maatschappelijke participatie' => 'maatschappelijkeParticipatie',
            'Justitie' => 'justitie',
        ];

        foreach (range(1, 5) as $score) {
            $data = [];
            foreach ($leefgebieden as $title => $leefgebied) {
                $result = $this->em->createQueryBuilder()
                    ->select("postcodegebied.naam AS groep, COUNT(zrm.{$leefgebied}) AS aantal")
                    ->from(ZrmV1::class, 'zrm')
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
