<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait DocumentSubjectTrait
{
    /**
     * @var Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $documenten;

    /**
     * @return Document[]
     */
    public function getDocumenten()
    {
        return $this->documenten;
    }

    /**
     * @param Document $document
     *
     * @return self
     */
    public function addDocument(Document $document)
    {
        $this->documenten[] = $document;

        return $this;
    }

    /**
     * @param Document $document
     *
     * @return self
     */
    public function removeDocument(Document $document)
    {
        $this->documenten->removeElement($document);

        return $this;
    }
}
