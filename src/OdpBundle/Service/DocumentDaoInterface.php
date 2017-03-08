<?php

namespace OdpBundle\Service;

interface DocumentDaoInterface
{
    /**
     * @param string $filename
     *
     * @return Document
     */
    public function findByFilename($filename);
}
