<?php

namespace GaBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Vrijwilligerdossier;

class VrijwilligerdossierDao extends AbstractDao implements VrijwilligerdossierDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.id',
            'vrijwilliger.achternaam',
            'werkgebied.naam',
            'groep.naam',
            'medewerker.voornaam',
            'dossier.aanmelddatum',
            'dossier.afsluitdatum',
        ],
    ];

    protected $class = Vrijwilligerdossier::class;

    protected $alias = 'dossier';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, vrijwilliger, werkgebied, lidmaatschap, groep")
            ->innerJoin("{$this->alias}.vrijwilliger", 'vrijwilliger')
            ->leftJoin($this->alias.'.medewerker', 'medewerker')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->leftJoin("{$this->alias}.lidmaatschappen", 'lidmaatschap')
            ->leftJoin('lidmaatschap.groep', 'groep')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @return Vrijwilligerdossier
     */
    public function findOneByVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        return $this->repository->findOneBy(['vrijwilliger' => $vrijwilliger]);
    }

    public function create(Vrijwilligerdossier $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Vrijwilligerdossier $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Vrijwilligerdossier $entity)
    {
        $this->doDelete($entity);
    }
}
