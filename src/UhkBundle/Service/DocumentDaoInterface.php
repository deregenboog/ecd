<?php

namespace UhkBundle\Service;

use UhkBundle\Entity\Document;

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

    public function create(Document $document);

    public function update(Document $document);

    public function delete(Document $document);
}
