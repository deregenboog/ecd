<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
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
 * @ORM\Table(name="mw_documenten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Vich\Uploadable
 */
class Document implements DocumentInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var string
     * @ORM\Column
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @var string
     * @ORM\Column
     * @Gedmo\Versioned
     */
    private $filename;

    /**
     * @var File
     * @Vich\UploadableField(mapping="mw_document", fileNameProperty="filename")
     * @Gedmo\Versioned
     */
    private $file;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker", cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $medewerker;

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

    public function __construct($klant = null, Medewerker $medewerker = null)
    {
        if ($klant instanceof Klant) {
            $this->klant = $klant;
        }

        if ($medewerker) {
            $this->medewerker = $medewerker;
        }
    }

    public function __toString()
    {
        return $this->naam;
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
