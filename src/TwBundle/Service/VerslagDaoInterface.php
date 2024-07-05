<?php

namespace TwBundle\Service;

use TwBundle\Entity\Verslag;

interface VerslagDaoInterface
{
    /**
     * @param int $id
     *
     * @return Verslag
     */
    public function find($id);

    public function create(Verslag $verslag);

    public function update(Verslag $verslag);

    public function delete(Verslag $verslag);
}
