<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Opmerking;

interface OpmerkingDaoInterface
{
    /**
     * @param Opmerking $entity
     *
     * @return Opmerking
     */
    public function create(Opmerking $entity);

    /**
     * @param Opmerking $entity
     *
     * @return Opmerking
     */
    public function update(Opmerking $entity);

    /**
     * @param Opmerking $entity
     *
     * @return Opmerking
     */
    public function delete(Opmerking $entity);
}
