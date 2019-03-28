<?php

namespace AppBundle\Entity;

use AppBundle\Model\DocumentInterface;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="documenten")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string", length=15)
 * @ORM\DiscriminatorMap({
 *     "document" = "Document",
 *     "vog" = "Vog",
 *     "overeenkomst" = "Overeenkomst"
 * })
 * @Gedmo\Loggable
 * @Vich\Uploadable
 */
class Document implements DocumentInterface
{
    use IdentifiableTrait, TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @var string
     * @ORM\Column
     * @Gedmo\Versioned
     */
    protected $filename;

    /**
     * @var File
     * @Vich\UploadableField(mapping="app_document", fileNameProperty="filename")
     */
    protected $file;

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(File $file = null)
    {
        $this->file = $file;

        return $this;
    }
}
