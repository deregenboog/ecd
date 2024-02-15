<?php

namespace GaBundle\Service;

use GaBundle\Entity\Groep;
use GaBundle\Entity\Vrijwilligerdossier;

class VrijwilligerLidmaatschapDao extends LidmaatschapDao
{
    protected $paginationOptions = [
        'pageParameterName' => 'page_vrijwilliger',
        'sortFieldParameterName' => 'sort_vrijwilliger',
        'sortDirectionParameterName' => 'direction_vrijwilliger',
        'defaultSortFieldName' => 'vrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.achternaam',
            'lidmaatschap.startdatum',
            'werkgebied.naam',
        ],
    ];

    public function findByGroep(Groep $groep, $page = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin(Vrijwilligerdossier::class, 'dossier', 'WITH', $this->alias.'.dossier = dossier')
            ->innerJoin('dossier.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->where($this->alias.'.groep = :groep')
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum > :today")
            ->setParameter('groep', $groep)
            ->setParameter('today', new \DateTime('today'))
        ;

        return $this->doFindAll($builder, $page);
    }
}
