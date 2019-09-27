<?php

namespace GaBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\VrijwilligerIntake;

class VrijwilligerIntakeDao extends AbstractDao implements VrijwilligerIntakeDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.id',
            'vrijwilliger.achternaam',
            'vrijwilliger.geboortedatum',
            'medewerker.voornaam',
            'intake.intakedatum',
            'intake.afsluitdatum',
        ],
    ];

    protected $class = VrijwilligerIntake::class;

    protected $alias = 'intake';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, vrijwilliger, medewerker")
            ->innerJoin("{$this->alias}.vrijwilliger", 'vrijwilliger')
            ->leftJoin('vrijwilliger.medewerker', 'medewerker')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param Vrijwilliger $vrijwilliger
     *
     * @return VrijwilligerIntake
     */
    public function findOneByVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        return $this->repository->findOneBy(['vrijwilliger' => $vrijwilliger]);
    }

    public function create(VrijwilligerIntake $entity)
    {
        $this->doCreate($entity);
    }

    public function update(VrijwilligerIntake $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(VrijwilligerIntake $entity)
    {
        $this->doDelete($entity);
    }
}
