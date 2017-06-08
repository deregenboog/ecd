<?php

namespace AppBundle\Export;

interface ExportInterface
{
    /**
     * @return ExportInterface
     */
    public function create();

    /**
     * @param string $filename
     *
     * @throws ExportException
     */
    public function send($filename);
}
