<?php

namespace InloopBundle\Service;

use InloopBundle\Entity\Document;

interface DocumentDaoInterface
{
    /**
     * @param string $id
     *
     * @return Document
     */
    public function find($id);

    /**
     * @param string $filename
     *
     * @return Document
     */
    public function findByFilename($filename);

    /**
     * @param Document $document
     */
    public function create(Document $document);

    /**
     * @param Document $document
     */
    public function update(Document $document);

    /**
     * @param Document $document
     */
    public function delete(Document $document);
}
