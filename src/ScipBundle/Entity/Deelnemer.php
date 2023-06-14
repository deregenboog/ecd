<?php

namespace ScipBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="scip_deelnemers",
 *     indexes={}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Deelnemer implements DocumentSubjectInterface
{
    use IdentifiableTrait;
    use ActivatableTrait;
    use DocumentSubjectTrait;
    use TimestampableTrait;

    public const TYPE_WMO = 'WMO';
    public const TYPE_ONDERAANNEMER = 'onderaannemer';

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="deelnemer", cascade={"persist"})
     */
    private $deelnames;

    /**
     * @var ArrayCollection|Label[]
     *
     * @ORM\ManyToMany(targetEntity="Label")
     */
    private $labels;

    private $statussen;

    /**
     * Any of self::TYPE_*.
     *
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $functie;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $werkbegeleider;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $risNummer;

    /**
     * @var Medewerker
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @var Collection|Verslag[]
     *
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="deelnemer", cascade={"persist"})
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var Collection|Werkdoel[]
     *
     * @ORM\OneToMany(targetEntity="Werkdoel", mappedBy="deelnemer", cascade={"persist"})
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $werkdoelen;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $evaluatiedatum;

    public function __construct(Klant $klant = null)
    {
        $this->klant = $klant;
        $this->deelnames = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
        $this->werkdoelen = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    /**
     * @return Klant
     */
    public function getKlant()
    {
        return $this->klant;
    }

    /**
     * @param Klant $klant
     */
    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    /**
     * @return Collection|Deelname[]
     */
    public function getDeelnames()
    {
        return $this->deelnames;
    }

    /**
     * @param Deelname $deelname
     */
    public function addDeelname(Deelname $deelname)
    {
        $this->deelnames[] = $deelname;

        return $this;
    }

    /**
     * @return Collection|Label[]
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param Label $label
     */
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    public function getProjecten()
    {
        foreach ($this->deelnames as $deelname) {
            yield $deelname->getProject();
        }
    }

    /**
     * @return mixed
     */
    public function getStatussen()
    {
        return $this->statussen;
    }

    /**
     * @param mixed $statussen
     */
    public function setStatussen($statussen)
    {
        $this->statussen = $statussen;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Medewerker
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * @param \AppBundle\Entity\Medewerker $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @param Ambigous <\Doctrine\Common\Collections\Collection, multitype:\ScipBundle\Entity\Deelname > $deelnames
     */
    public function setDeelnames($deelnames)
    {
        $this->deelnames = $deelnames;

        return $this;
    }

    /**
     * @param Ambigous <\Doctrine\Common\Collections\Collection, multitype:\ScipBundle\Entity\Label > $labels
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return string
     */
    public function getFunctie()
    {
        return $this->functie;
    }

    /**
     * @param string $functie
     */
    public function setFunctie(?string $functie = null)
    {
        $this->functie = $functie;

        return $this;
    }

    /**
     * @return string
     */
    public function getWerkbegeleider()
    {
        return $this->werkbegeleider;
    }

    /**
     * @param string $werkbegeleider
     */
    public function setWerkbegeleider(?string $werkbegeleider = null)
    {
        $this->werkbegeleider = $werkbegeleider;

        return $this;
    }

    /**
     * @return string
     */
    public function getRisNummer()
    {
        return $this->risNummer;
    }

    /**
     * @param string $risNummer
     */
    public function setRisNummer(?string $risNummer = null)
    {
        $this->risNummer = $risNummer;

        return $this;
    }

    /**
     * @return Collection|Verslag[]
     */
    public function getVerslagen()
    {
        return $this->verslagen;
    }

    /**
     * @param Verslag $verslag
     */
    public function addVerslag(Verslag $verslag)
    {
        $verslag->setDeelnemer($this);
        $this->verslagen[] = $verslag;

        return $this;
    }

    /**
     * @return Collection|Werkdoel[]
     */
    public function getWerkdoelen()
    {
        return $this->werkdoelen;
    }

    /**
     * @param Werkdoel $werkdoel
     */
    public function addWerkdoel(Werkdoel $werkdoel)
    {
        $werkdoel->setDeelnemer($this);
        $this->werkdoelen[] = $werkdoel;

        return $this;
    }

    public function hasVog()
    {
        foreach ($this->documenten as $document) {
            if (Document::TYPE_VOG === $document->getType()) {
                return true;
            }
        }

        return false;
    }

    public function hasOvereenkomst()
    {
        foreach ($this->documenten as $document) {
            if (Document::TYPE_OVEREENKOMST === $document->getType()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \DateTime
     */
    public function getEvaluatiedatum(): ?\DateTime
    {
        return $this->evaluatiedatum;
    }

    /**
     * @param \DateTime $evaluatieDatum
     */
    public function setEvaluatiedatum(?\DateTime $evaluatiedatum): void
    {
        $this->evaluatiedatum = $evaluatiedatum;
    }
}
