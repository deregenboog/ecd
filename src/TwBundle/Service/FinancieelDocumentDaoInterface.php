<?php

namespace TwBundle\Service;

use TwBundle\Entity\FinancieelDocument;

interface FinancieelDocumentDaoInterface
{
    /**
     * @param string $id
     *
     * @return FinancieelDocument
     */
    public function find($id);

    /**
     * @param string $filename
     *
     * @return FinancieelDocument
     */
    public function findByFilename($filename);

    public function create(FinancieelDocument $document);

    public function update(FinancieelDocument $document);

    public function delete(FinancieelDocument $document);
}
