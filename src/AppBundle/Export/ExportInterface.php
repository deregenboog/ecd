<?php

namespace AppBundle\Export;

use Doctrine\Common\Collections\ArrayCollection;

interface ExportInterface
{
    /**
     * @param array|ArrayCollection $entities
     * @return ExportInterface
     */
    public function create($entities);

    /**
     * @param string $filename
     *
     * @throws ExportException
     */
    public function send($filename);
}
