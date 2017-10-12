<?php

namespace HsBundle\Entity;

interface DocumentSubjectInterface
{
    /**
     * @return Document[]
     */
    public function getDocumenten();

    /**
     * @param Document $document
     *
     * @return self
     */
    public function addDocument(Document $document);
}
