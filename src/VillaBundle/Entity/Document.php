<?php

namespace VillaBundle\Entity;

use AppBundle\Entity\Medewerker;
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
 *
 * @ORM\Table(name="villa_documenten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 *
 * @Vich\Uploadable
 */
class Document implements DocumentInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

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
     * @Vich\UploadableField(mapping="villa_document", fileNameProperty="filename")
     */
    private $file;

    /**
     * @var Medewerker
     */
    protected $medewerker;

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

    public function setFile(?File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }
}
