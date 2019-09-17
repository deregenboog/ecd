<?php

namespace OdpBundle\Service;


use OdpBundle\Entity\FinancieelDocument;

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

    /**
     * @param FinancieelDocument $document
     */
    public function create(FinancieelDocument $document);

    /**
     * @param FinancieelDocument $document
     */
    public function update(FinancieelDocument $document);

    /**
     * @param FinancieelDocument $document
     */
    public function delete(FinancieelDocument $document);
}
