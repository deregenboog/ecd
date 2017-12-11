<?php

namespace AppBundle\Report;

use AppBundle\Report\Table;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\ZrmV2Report;

class ZrmV2ScorePerPostcodegebied extends AbstractReport
{
    protected $title = 'ZRM(v2)-score per postcodegbied';

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
            'Financiën' => 'financien',
            'Werk en opleiding' => 'werk_opleiding',
            'Tijdsbesteding' => 'tijdsbesteding',
            'Huisvesting' => 'huisvesting',
            'Huiselijke relaties' => 'huiselijke_relaties',
            'Geestelijke gezondheid' => 'geestelijke_gezondheid',
            'Lichamelijke gezondheid' => 'lichamelijke_gezondheid',
            'Middelengebruik' => 'middelengebruik',
            'Basale ADL' => 'basale_adl',
            'Instrumentele ADL' => 'instrumentele_adl',
            'Sociaal netwerk' => 'sociaal_netwerk',
            'Maatschappelijke participatie' => 'maatschappelijke_participatie',
            'Justitie' => 'justitie',
        ];

        foreach (range(1, 5) as $score) {
            $data = [];
            foreach ($leefgebieden as $title => $leefgebied) {
                $result = $this->em->createQueryBuilder()
                    ->select("klant.postcodegebied, COUNT(zrm.{$leefgebied}) AS aantal")
                    ->from(ZrmV2Report::class, 'zrm')
                    ->innerJoin('zrm.klant', 'klant')
                    ->where("zrm.{$leefgebied} = {$score}")
                    ->andWhere('DATE(zrm.created) BETWEEN :start AND :end')
                    ->groupBy('klant.postcodegebied')
                    ->orderBy('klant.postcodegebied')
                    ->setParameters([
                        'start' => $this->startDate,
                        'end' => $this->endDate,
                    ])
                    ->getQuery()
                    ->getResult();

                foreach ($result as $item) {
                    $data[] = [
                        'leefgebied' => $title,
                        'postcodegebied' => $item['postcodegebied'],
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
