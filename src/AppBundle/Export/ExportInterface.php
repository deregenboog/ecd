<?php

namespace AppBundle\Export;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;

interface ExportInterface
{
    /**
     * @param array|ArrayCollection $entities
     *
     * @return ExportInterface
     */
    public function create($entities);

    /**
     * @param string $filename
     *
     * @return Response
     *
     * @throws ExportException
     */
    public function getResponse($filename);

    /**
     * @param string $filename
     *
     * @throws ExportException
     */
    public function send($filename);
}
