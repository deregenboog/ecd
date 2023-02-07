<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="MwBundle\Repository\DossierStatusRepository")
 * @ORM\Table(name="mw_dossier_statussen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "Aanmelding" = "Aanmelding",
 *     "IntakeAlgemeen" = "IntakeAlgemeen",
 *     "IntakeHuisvesting" = "IntakeHuisvesting",
 *     "IntakeInkomen" = "IntakeInkomen",
 *     "IntakeWelzijn" = "IntakeWelzijn",
 *     "IntakeAdministratie" = "IntakeAdministratie",
 *     "IntakeVerwachting" = "IntakeVerwachting",
 *     "IntakeGezin" = "IntakeGezin",
 *     "Afsluiting" = "Afsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class DossierStatus
{
    use IdentifiableTrait;
    use RequiredMedewerkerTrait;
    use TimestampableTrait;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="mwStatussen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $datum;

    public function __construct(Klant $klant = null, Medewerker $medewerker = null)
    {
        if ($klant) {
            $this->klant = $klant;
            $klant->addDossierStatus($this);
        }

        $this->medewerker = $medewerker;
        $this->datum = new \DateTime('now');
    }

    public function getKlant()
    {
        return $this->klant;
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

    public function isAangemeld()
    {
        return $this instanceof Aanmelding;
    }

    public function isAfgesloten()
    {
        return $this instanceof Afsluiting;
    }
}
