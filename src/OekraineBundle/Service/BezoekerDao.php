<?php

namespace OekraineBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Bezoeker;

class BezoekerDao extends AbstractDao implements BezoekerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appKlant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'bezoeker.id',
            'appKlant.voornaam',
            'appKlant.achternaam',
            'appKlant.geboortedatum',
            'geslacht.volledig',
            'gebruikersruimte.naam',
            'laatsteIntakeLocatie.naam',
            'laatsteIntake.intakedatum',
            'laatsteIntake.woonlocatie.naam',
        ],
    ];

    protected $class = Bezoeker::class;

    protected $alias = 'bezoeker';
    protected $searchEntityName = 'appKlant';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->getAllQueryBuilder($filter);
        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function getAllQueryBuilder(?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', appKlant, intakes, geslacht')
//            ->innerJoin('appKlant.huidigeStatus', 'status')
            ->innerJoin($this->alias.'.appKlant', 'appKlant')
            ->leftJoin('appKlant.intakes', 'intakes')// , "WITH","appKlant.eersteIntake = intake")
            ->leftJoin('appKlant.geslacht', 'geslacht')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $builder;
    }

    public function findByKlantId($bezoekerId)
    {
        return $this->repository->findOneBy(['appKlant' => $bezoekerId]);
    }

    /**
     * @return Bezoeker
     */
    public function create(Bezoeker $entity)
    {
        //
        //        $aanmelding = new Aanmelding($entity);
        //        $entity->setHuidigeStatus($aanmelding);

        return parent::doCreate($entity);
    }

    /**
     * @return Bezoeker
     */
    public function update(Bezoeker $entity)
    {
        return parent::doUpdate($entity);
    }
}
