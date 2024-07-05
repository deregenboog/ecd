<?php

namespace OekraineBundle\Service;

use AppBundle\Entity\Opmerking;

interface OpmerkingDaoInterface
{
    /**
     * @return Opmerking
     */
    public function create(Opmerking $entity);

    /**
     * @return Opmerking
     */
    public function update(Opmerking $entity);

    /**
     * @return Opmerking
     */
    public function delete(Opmerking $entity);
}
