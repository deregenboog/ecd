<?php

namespace AppBundle\Model;

trait DocumentSubjectTrait
{
    /**
     * @var DocumentInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $documenten;

    /**
     * @return DocumentInterface[]
     */
    public function getDocumenten()
    {
        return $this->documenten;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return self
     */
    public function addDocument(DocumentInterface $document)
    {
        $this->documenten[] = $document;

        return $this;
    }

    /**
     * @param Document $document
     *
     * @return self
     */
    public function removeDocument(DocumentInterface $document)
    {
        $this->documenten->removeElement($document);

        return $this;
    }
}
