<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Klantdossier;

class KlantdossierDao extends AbstractDao implements KlantdossierDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'werkgebied.naam',
            'groep.naam',
            'dossier.aanmelddatum',
            'dossier.afsluitdatum',
        ],
    ];

    protected $class = Klantdossier::class;

    protected $alias = 'dossier';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, werkgebied, lidmaatschap, groep")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin("{$this->alias}.lidmaatschappen", 'lidmaatschap')
            ->leftJoin('lidmaatschap.groep', 'groep')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param Klant $klant
     *
     * @return KlantDossier
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    public function create(Klantdossier $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Klantdossier $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Klantdossier $entity)
    {
        $this->doDelete($entity);
    }
}
