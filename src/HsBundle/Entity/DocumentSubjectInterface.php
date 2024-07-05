<?php

namespace HsBundle\Entity;

interface DocumentSubjectInterface
{
    /**
     * @return Document[]
     */
    public function getDocumenten();

    /**
     * @return self
     */
    public function addDocument(Document $document);

    /**
     * @return self
     */
    public function removeDocument(Document $document);
}
