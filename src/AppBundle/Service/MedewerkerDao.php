<?php

namespace AppBundle\Service;

use AppBundle\Entity\Medewerker;

class MedewerkerDao extends AbstractDao implements MedewerkerDaoInterface
{
    protected $class = Medewerker::class;

    protected $alias = 'medewerker';

    /**
     * @param string $username
     *
     * @return Medewerker
     */
    public function find($username)
    {
        return $this->repository->findOneBy(['username' => $username]);
    }
}
