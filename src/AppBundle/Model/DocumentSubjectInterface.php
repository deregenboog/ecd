<?php

namespace AppBundle\Model;

interface DocumentSubjectInterface
{
    /**
     * @return DocumentInterface[]
     */
    public function getDocumenten();

    /**
     * @return self
     */
    public function addDocument(DocumentInterface $document);

    /**
     * @return self
     */
    public function removeDocument(DocumentInterface $document);
}
