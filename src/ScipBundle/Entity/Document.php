<?php

namespace ScipBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\DocumentInterface;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MedewerkerSubjectInterface;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="scip_documenten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 *
 * @Vich\Uploadable
 */
class Document implements DocumentInterface, MedewerkerSubjectInterface
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    public const TYPE_VOG = 'VOG';
    public const TYPE_OVEREENKOMST = 'Overeenkomst';

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Gedmo\Versioned
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="scip_document", fileNameProperty="filename")
     *
     * @Gedmo\Versioned
     */
    private $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(?Medewerker $medewerker = null)
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

    public function getType()
    {
        return $this->type;
    }

    public function setType(?string $type = null)
    {
        $this->type = $type;

        return $this;
    }
}
