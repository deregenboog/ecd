<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_declaraties")
 * @Gedmo\Loggable
 */
class Declaratie implements DocumentSubjectInterface
{
    use DocumentSubjectTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $info;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Gedmo\Versioned
     */
    private $bedrag;

    /**
     * @var Klus
     * @ORM\ManyToOne(targetEntity="Klus", inversedBy="declaraties")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $klus;

    /**
     * @var Factuur
     * @ORM\ManyToOne(targetEntity="Factuur", inversedBy="declaraties")
     * @Gedmo\Versioned
     */
    private $factuur;

    /**
     * @var DeclaratieCategorie
     * @ORM\ManyToOne(targetEntity="DeclaratieCategorie", inversedBy="declaraties")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $declaratieCategorie;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $medewerker;

    private $document;

    public function __construct(Klus $klus, Medewerker $medewerker = null)
    {
        $this->klus = $klus;
        $this->datum = $klus->getStartdatum();
        $this->medewerker = $medewerker;
        $this->documenten = new ArrayCollection();
        $this->datum = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getKlus()
    {
        return $this->klus;
    }

    public function getFactuur()
    {
        return $this->factuur;
    }

    public function setFactuur(Factuur $factuur)
    {
        $this->factuur = $factuur;

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

    public function getInfo()
    {
        return $this->info;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }

    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    public function setBedrag($bedrag)
    {
        $this->bedrag = $bedrag;

        return $this;
    }

    public function getDeclaratieCategorie()
    {
        return $this->declaratieCategorie;
    }

    public function setDeclaratieCategorie(DeclaratieCategorie $declaratieCategorie)
    {
        $this->declaratieCategorie = $declaratieCategorie;

        return $this;
    }

    /**
     * @return Document
     */
    public function getDocument()
    {
        if (count($this->documenten) > 0) {
            return $this->documenten[0];
        }
    }

    /**
     * @param Document $document
     *
     * @return self
     */
    public function setDocument(Document $document)
    {
        if (!$document->getNaam()) {
            $document->setNaam('Foto bij declaratie');
        }

        if (!$document->getMedewerker()) {
            $document->setMedewerker($this->getMedewerker());
        }

        $this->documenten[0] = $document;

        return $this;
    }
}
