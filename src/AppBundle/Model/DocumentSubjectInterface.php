<?php

namespace AppBundle\Model;

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

    /**
     * @param Document $document
     *
     * @return self
     */
    public function removeDocument(Document $document);
}
