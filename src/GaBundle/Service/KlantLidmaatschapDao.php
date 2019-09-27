<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use GaBundle\Entity\Groep;
use GaBundle\Entity\Klantdossier;

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

    public function findByGroep(Groep $groep, $page = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin(Klantdossier::class, 'dossier', 'WITH', $this->alias.'.dossier = dossier')
            ->innerJoin('dossier.klant', 'klant')
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
