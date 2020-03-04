<?php

namespace MwBundle\Report;

use AppBundle\Entity\Klant;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\Afsluiting;

class RepatrieringenPerLand extends AbstractReport
{
    protected $title = 'Repatriëringen per land van bestemming';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Land';

    protected $tables = [];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function init()
    {
        $builder = $this->entityManager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select('COUNT(klant.id) AS aantal')
            ->addSelect('land.land AS groep')
            ->innerJoin(Afsluiting::class, 'afsluiting', 'WITH', 'klant.huidigeStatus = afsluiting')
            ->innerJoin('afsluiting.land', 'land')
            ->where('afsluiting.datum BETWEEN :start_date AND :end_date')
            ->groupBy('land.id')
            ->orderBy('aantal', 'DESC')
            ->setParameters([
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ])
        ;

        $this->tables[''] = $builder->getQuery()->getResult();
    }

    protected function build()
    {
        foreach ($this->tables as $title => $data) {
            $table = new Table($data, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false)->setYSort(false);

            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
