<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Entity\KlantLidmaatschap;
use GaBundle\Entity\Groep;
use AppBundle\Entity\Klant;

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

    protected $class = KlantLidmaatschap::class;

    public function findByGroep(Groep $groep, $page = null)
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

    public function findOneByGroepAndKlant(Groep $groep, Klant $klant)
    {
        return $this->repository->findOneBy([
            'groep' => $groep,
            'klant' => $klant,
        ]);
    }
}
