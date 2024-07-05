<?php

namespace TwBundle\Service;

use TwBundle\Entity\Deelname;

interface DeelnameDaoInterface
{
    /**
     * @param int $id
     *
     * @return Deelname
     */
    public function find($id);

    public function create(Deelname $entity);

    public function update(Deelname $entity);

    public function delete(Deelname $entity);
}
