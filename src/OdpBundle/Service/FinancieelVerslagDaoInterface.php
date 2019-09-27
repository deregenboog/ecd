<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\FinancieelVerslag;

interface FinancieelVerslagDaoInterface
{
    /**
     * @param int $id
     *
     * @return FinancieelVerslag
     */
    public function find($id);

    /**
     * @param FinancieelVerslag $verslag
     */
    public function create(FinancieelVerslag $verslag);

    /**
     * @param FinancieelVerslag $verslag
     */
    public function update(FinancieelVerslag $verslag);

    /**
     * @param FinancieelVerslag $verslag
     */
    public function delete(FinancieelVerslag $verslag);
}
