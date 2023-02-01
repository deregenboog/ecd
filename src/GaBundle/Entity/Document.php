<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\DocumentInterface;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="ga_documenten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Vich\Uploadable
 */
class Document implements DocumentInterface
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var string
     * @ORM\Column
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var string
     * @ORM\Column
     * @Gedmo\Versioned
     */
    private $filename;

    /**
     * @var File
     * @Vich\UploadableField(mapping="ga_document", fileNameProperty="filename")
     * @Gedmo\Versioned
     */
    private $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(Medewerker $medewerker = null)
    {
        if ($medewerker) {
            $this->medewerker = $medewerker;
        }
    }

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

    public function setFile(File $file)
    {
        $this->file = $file;

        return $this;
    }
}
