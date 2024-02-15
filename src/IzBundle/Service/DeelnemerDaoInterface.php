<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzDeelnemer;

interface DeelnemerDaoInterface
{
    /**
     * @param int $id
     *
     * @return IzDeelnemer
     */
    public function find($id);

    public function update(IzDeelnemer $entity);
}
