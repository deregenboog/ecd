<?php

namespace VillaBundle\Service;

use VillaBundle\Entity\Overnachting;

interface OvernachtingDaoInterface
{
    /**
     * @param string $id
     *
     * @return Overnachting
     */
    public function find($id);

    public function findOvernachtingenByEntityIdForDateRange(int $entityId, $start, $end);

    /**
     * @param Overnachting $overnachting
     */
    public function create(Overnachting $overnachting);

    /**
     * @param Overnachting $overnachting
     */
    public function update(Overnachting $overnachting);

    /**
     * @param Overnachting $overnachting
     */
    public function delete(Overnachting $overnachting);
}
