<?php

namespace GaBundle\Entity;

use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="ga_activiteiten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Activiteit
{
    use TimestampableTrait, NotDeletableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $afgesloten = false;

    /**
     * @var ArrayCollection|Deelname[]
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="activiteit", cascade={"persist"})
     */
    private $deelnames;

    /**
     * @var Groep
     *
     * @ORM\ManyToOne(targetEntity="Groep", inversedBy="activiteiten")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $groep;

    /**
     * @var ActiviteitAnnuleringsreden
     *
     * @ORM\ManyToOne(targetEntity="ActiviteitAnnuleringsreden")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $annuleringsreden;

    /**
     * @var int
     *
     * @ORM\Column()
     * @Gedmo\Versioned
     * @Assert\GreaterThanOrEqual(0)
     */
    private $aantalAnoniemeDeelnemers = 0;

    /**
     * @return int
     */
    public function getAantalAnoniemeDeelnemers()
    {
        return (int) $this->aantalAnoniemeDeelnemers;
    }

    /**
     * @param int $aantalAnoniemeDeelnemers
     */
    public function setAantalAnoniemeDeelnemers($aantalAnoniemeDeelnemers)
    {
        $this->aantalAnoniemeDeelnemers = $aantalAnoniemeDeelnemers;

        return $this;
    }

    public function __construct()
    {
        $this->deelnames = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->naam, $this->datum->format('d-m-Y'));
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

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum($datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getGroep()
    {
        return $this->groep;
    }

    public function setGroep(Groep $groep = null)
    {
        $this->groep = $groep;

        return $this;
    }

    public function isActief()
    {
        return !$this->afgesloten;
    }

    public function setActief($actief)
    {
        $this->afgesloten = !$actief;

        return $this;
    }

    public function getDeelname(Dossier $dossier)
    {
        foreach ($this->deelnames as $deelname) {
            if ($deelname->getDossier() == $dossier) {
                return $dossier;
            }
        }
    }

    public function getDeelnames()
    {
        return $this->deelnames;
    }

    public function addDeelname(Deelname $deelname)
    {
        $this->deelnames[] = $deelname;
        $deelname->setActiviteit($this);

        return $this;
    }

    public function getDossiers()
    {
        $dossiers = [];
        foreach ($this->deelnames as $deelname) {
            $dossiers[] = $deelname->getDossier();
        }
        $dossiers = array_filter($dossiers);

        return new ArrayCollection($dossiers);
    }

    public function getKlantDeelnames()
    {
        return array_filter(
            $this->deelnames->toArray(),
            function (Deelname $deelname) {
                return $deelname->getDossier() instanceof Klantdossier;
            }
        );
    }

    public function getNumKlantDeelnames(): int
    {
        return (is_array($this->getKlantDeelnames()) || $this->getKlantDeelnames() instanceof \Countable ? count($this->getKlantDeelnames()) : 0) + $this->aantalAnoniemeDeelnemers;
    }

    public function getVrijwilligerDeelnames()
    {
        return array_filter(
            $this->deelnames->toArray(),
            function (Deelname $deelname) {
                return $deelname->getDossier() instanceof Vrijwilligerdossier;
            }
        );
    }

    public function getNumVrijwilligerDeelnames(): int
    {
        return is_array($this->getVrijwilligerDeelnames()) || $this->getVrijwilligerDeelnames() instanceof \Countable ? count($this->getVrijwilligerDeelnames()) : 0;
    }

    public function getAnnuleringsreden()
    {
        return $this->annuleringsreden;
    }

    public function setAnnuleringsreden(ActiviteitAnnuleringsreden $annuleringsreden)
    {
        $this->annuleringsreden = $annuleringsreden;

        return $this;
    }

    public function isInVerleden()
    {
        return new \DateTime() > $this->datum;
    }

    public function isInToekomst()
    {
        return $this->datum > new \DateTime();
    }

    public function isGeannuleerd()
    {
        return !is_null($this->annuleringsreden);
    }
}
