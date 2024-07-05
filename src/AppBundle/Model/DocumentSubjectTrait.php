<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;

trait DocumentSubjectTrait
{
    /**
     * @var DocumentInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist","remove"}, fetch="EXTRA_LAZY")
     *
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true, onDelete="CASCADE")})
     */
    protected $documenten;

    /**
     * @return DocumentInterface[]
     */
    public function getDocumenten()
    {
        // if(!is_array($this->documenten)) $this->documenten = array();
        return $this->documenten;
    }

    /**
     * @return self
     */
    public function addDocument(DocumentInterface $document)
    {
        $this->documenten[] = $document;

        return $this;
    }

    /**
     * @return self
     */
    public function removeDocument(DocumentInterface $document)
    {
        $this->documenten->removeElement($document);

        return $this;
    }
}
