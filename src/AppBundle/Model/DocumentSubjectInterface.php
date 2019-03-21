<?php

namespace AppBundle\Model;

interface DocumentSubjectInterface
{
    /**
     * @return DocumentInterface[]
     */
    public function getDocumenten();

    /**
     * @param DocumentInterface $document
     *
     * @return self
     */
    public function addDocument(DocumentInterface $document);

    /**
     * @param DocumentInterface $document
     *
     * @return self
     */
    public function removeDocument(DocumentInterface $document);
}
