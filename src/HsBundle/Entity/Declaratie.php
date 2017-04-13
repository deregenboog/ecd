<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use AppBundle\Entity\Medewerker;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="hs_declaraties")
 */
class Declaratie implements DocumentSubjectInterface
{
    use DocumentSubjectTrait;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     * @Column(type="date")
     */
    private $datum;

    /**
     * @var string
     * @Column(type="text")
     */
    private $info;

    /**
     * @var float
     * @Column(type="float")
     */
    private $bedrag;

    /**
     * @var Klus
     * @ManyToOne(targetEntity="Klus", inversedBy="declaraties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $klus;

    /**
     * @var Factuur
     * @ManyToOne(targetEntity="Factuur", inversedBy="declaraties")
     */
    private $factuur;

    /**
     * @var DeclaratieCategorie
     * @ManyToOne(targetEntity="DeclaratieCategorie", inversedBy="declaraties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $declaratieCategorie;

    /**
     * @var Medewerker
     * @ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    private $document;

    public function __construct(Klus $klus, Medewerker $medewerker = null)
    {
        $this->klus = $klus;
        $this->datum = $klus->getDatum();
        $this->medewerker = $medewerker;
        $this->documenten = new ArrayCollection();
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
