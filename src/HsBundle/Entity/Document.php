<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_documenten")
 * @ORM\HasLifecycleCallbacks
 *
 * @Vich\Uploadable
 */
class Document
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column
     *
     * @var string
     */
    private $naam;

    /**
     * @ORM\Column
     *
     * @var string
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="hs_document", fileNameProperty="filename")
     *
     * @var File
     */
    private $file;

    public function __construct(Medewerker $medewerker = null)
    {
        if ($medewerker) {
            $this->medewerker = $medewerker;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;
        return $this;
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
