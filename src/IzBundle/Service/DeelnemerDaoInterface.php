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

    /**
     * @param IzDeelnemer $entity
     *
     * @return IzDeelnemer
     */
    public function update(IzDeelnemer $entity);
}
