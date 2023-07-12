<?php

namespace DagbestedingBundle\Entity;

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
 * @ORM\Table(name="dagbesteding_documenten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Vich\Uploadable
 */
class Document
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;


    public const TYPE_DIVERS = 'Divers';
    public const TYPE_TOESTEMMINGSVERKLARING = 'Toestemmingsverklaring';
    public const TYPE_ONDERSTEUNINGSPLAN = 'Ondersteuningsplan';
    public const TYPE_CV = 'CV';
    public const TYPE_VOORSTELPROFIEL= 'Voorstelprofiel';


    public const TYPES = [
        'Divers' => self::TYPE_DIVERS,
        'Toestemmingsverklaring' => self::TYPE_TOESTEMMINGSVERKLARING,
        'Ondersteuningsplan' => self::TYPE_ONDERSTEUNINGSPLAN,
        'CV' => self::TYPE_CV,
        'Voorstelprofiel'=> self::TYPE_VOORSTELPROFIEL,
    ];

    /**
     * @var string
     * @ORM\Column
     * @Gedmo\Versioned
     */
    private $filename;

    /**
     * @var File
     * @Vich\UploadableField(mapping="dagbesteding_document", fileNameProperty="filename")
     */
    private $file;

    /**
     * @var Deelnemer
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Deelnemer", inversedBy="documenten")
     */
    private $deelnemer;

    /**
     * @var int
     * @ORM\Column()
     * @deprecated Can be removed after succesful migration of data // 20230712 JTB
     */
    private $traject_id;

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

    /**
     * @var string
     * @ORM\Column()
     */
    protected $type = self::TYPE_DIVERS;


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

    /**
     * @return Deelnemer
     */
    public function getDeelnemer(): Deelnemer
    {
        return $this->deelnemer;
    }

    /**
     * @param Deelnemer $deelnemer
     */
    public function setDeelnemer(Deelnemer $deelnemer): void
    {
        $this->deelnemer = $deelnemer;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }



}
