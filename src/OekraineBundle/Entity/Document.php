<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\DocumentInterface;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="oekraine_documenten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 *
 * @Vich\Uploadable
 */
class Document implements DocumentInterface
{
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Gedmo\Versioned
     */
    private $filename;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="oekraine_document", fileNameProperty="filename")
     *
     * @Gedmo\Versioned
     */
    private $file;

    public function __construct(?Medewerker $medewerker = null)
    {
        if ($medewerker) {
            $this->medewerker = $medewerker;
        }
    }

    public function __toString()
    {
        return $this->naam;
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
