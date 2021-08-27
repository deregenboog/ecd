<?php

namespace AppBundle\Service;

use AppBundle\Entity\Medewerker;

class MedewerkerDao extends AbstractDao implements MedewerkerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.username',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'medewerker.username',
            'medewerker.achternaam',
        ],
    ];

    protected $class = Medewerker::class;

    protected $alias = 'medewerker';

    /**
     * @param int $id
     *
     * @return Medewerker
     */
    public function find($id)
    {
//        return $this->repository->findOneBy(['username' => $username]);
        return $this->repository->find($id);
    }

    /**
     * @param Medewerker $entity
     */
    public function update($entity)
    {
        return parent::doUpdate($entity);
    }
}
