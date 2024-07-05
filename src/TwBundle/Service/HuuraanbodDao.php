<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Huurovereenkomst;

class HuuraanbodDao extends AbstractDao
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'huuraanbod.id',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'huuraanbod.id',
            'appKlant.achternaam',
            'werkgebied.naam',
            'appKlant.pplaats',
            'huuraanbod.startdatum',
            'huuraanbod.afsluitdatum',
            'huuraanbod.datumToestemmingAangevraagd',
            'huuraanbod.datumToestemmingToegekend',
            'huurovereenkomst.isReservering',
            'medewerker.achternaam',
            'project.naam',
            'huuraanbod.isActief',
        ],
    ];

    protected $class = Huuraanbod::class;

    protected $alias = 'huuraanbod';

    protected function doFindAll(QueryBuilder $builder, $page = 1, ?FilterInterface $filter = null)
    {
        $builder
            ->leftJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('verhuurder.appKlant', 'appKlant')
            ->innerJoin('huuraanbod.medewerker', 'medewerker')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->leftJoin('huuraanbod.afsluiting', 'afsluiting')
            ->leftJoin('huuraanbod.project', 'project')
            ->andWhere('huurovereenkomst.id IS NULL') // alleen actieve
//            ->andWhere('huuraanbod.afsluitdatum IS NULL OR huuraanbod.afsluitdatum > :now') // alleen actieve
//            ->orWhere('huurovereenkomst.isReservering = 1')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;
        $builder->setParameter('now', new \DateTime('now'));

        return parent::doFindAll($builder, $page, $filter);
    }

    public function create(Huuraanbod $huuraanbod)
    {
        $this->doCreate($huuraanbod);
    }

    public function update(Huuraanbod $huuraanbod)
    {
        $this->doUpdate($huuraanbod);
    }

    public function delete(Huuraanbod $huuraanbod)
    {
        $this->doDelete($huuraanbod);
    }
}
