<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Verslag;

interface VerslagDaoInterface
{
    /**
     * @param int $id
     *
     * @return Verslag
     */
    public function find($id);

    /**
     * @param Verslag $verslag
     */
    public function create(Verslag $verslag);

    /**
     * @param Verslag $verslag
     */
    public function update(Verslag $verslag);

    /**
     * @param Verslag $verslag
     */
    public function delete(Verslag $verslag);
}
