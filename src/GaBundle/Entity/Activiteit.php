<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten")
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
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(name="time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $tijd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $afgesloten = false;

    /**
     * @var ArrayCollection|KlantDeelname[]
     *
     * @ORM\OneToMany(targetEntity="KlantDeelname", mappedBy="activiteit", cascade={"persist"})
     */
    private $klantDeelnames;

    /**
     * @var ArrayCollection|VrijwilligerDeelname[]
     *
     * @ORM\OneToMany(targetEntity="VrijwilligerDeelname", mappedBy="activiteit", cascade={"persist"})
     */
    private $vrijwilligerDeelnames;

    /**
     * @var Groep
     *
     * @ORM\ManyToOne(targetEntity="Groep", inversedBy="activiteiten")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $groep;

    public function __construct()
    {
        $this->klantDeelnames = new ArrayCollection();
        $this->vrijwilligerDeelnames = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf(
            '%s (%s | %s)',
            $this->naam,
            $this->datum->format('d-m-Y'),
            $this->tijd->format('H:i')
        );
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

    public function getTijd()
    {
        return $this->tijd;
    }

    public function setTijd($tijd)
    {
        $this->tijd = $tijd;

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

    public function getKlantDeelname(Klant $klant)
    {
        foreach ($this->klantDeelnames as $deelname) {
            if ($deelname->getKlant() == $klant) {
                return $deelname;
            }
        }
    }

    public function getKlantDeelnames()
    {
        return $this->klantDeelnames;
    }

    public function getVrijwilligerDeelnames()
    {
        return $this->vrijwilligerDeelnames;
    }

    public function addKlantDeelname(KlantDeelname $deelname)
    {
        $this->klantDeelnames[] = $deelname;
        $deelname->setActiviteit($this);

        return $this;
    }

    public function addVrijwilligerDeelname(VrijwilligerDeelname $deelname)
    {
        $this->vrijwilligerDeelnames[] = $deelname;
        $deelname->setActiviteit($this);

        return $this;
    }

    public function getKlanten()
    {
        $dossiers = [];
        foreach ($this->klantDeelnames as $deelname) {
            $dossiers[] = $deelname->getKlant();
        }
        $dossiers = array_filter($dossiers);

        return new ArrayCollection($dossiers);
    }

    public function getVrijwilligers()
    {
        $dossiers = [];
        foreach ($this->vrijwilligerDeelnames as $deelname) {
            $dossiers[] = $deelname->getVrijwilliger();
        }
        $dossiers = array_filter($dossiers);

        return new ArrayCollection($dossiers);
    }
}
