<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use GaBundle\Entity\GaGroep;
use GaBundle\Entity\GaKlantLidmaatschap;

class KlantLidmaatschapDao extends LidmaatschapDao
{
    protected $paginationOptions = [
        'pageParameterName' => 'page_klant',
        'sortFieldParameterName' => 'sort_klant',
        'sortDirectionParameterName' => 'direction_klant',
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.achternaam',
            'lidmaatschap.startdatum',
            'werkgebied.naam',
        ],
    ];

    protected $class = GaKlantLidmaatschap::class;

    public function findByGroep(GaGroep $groep, $page = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', klant, werkgebied')
            ->innerJoin($this->alias.'.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->where($this->alias.'.groep = :groep')
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum > :today")
            ->setParameter('groep', $groep)
            ->setParameter('today', new \DateTime('today'))
        ;

        return $this->doFindAll($builder, $page);
    }

    public function findOneByGroepAndKlant(GaGroep $groep, Klant $klant)
    {
        return $this->repository->findOneBy([
            'gaGroep' => $groep,
            'klant' => $klant,
        ]);
    }
}
