<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use ClipBundle\Entity\RequiredBehandelaarTrait;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="MwBundle\Repository\DossierStatusRepository")
 * @ORM\Table(name="mw_dossier_statussen", indexes={
 *     @ORM\Index(name="class", columns={"class", "id", "klant_id"})
 * })
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "Aanmelding" = "Aanmelding",
 *     "Afsluiting" = "Afsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class MwDossierStatus
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="mwStatussen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     * @Assert\Type("AppBundle\Entity\Klant")
     */
    protected $klant;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     * @Assert\Type("\DateTimeInterface")
     */
    protected $datum;

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
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\Project", inversedBy="aanmeldingen")
     * @ORM\JoinColumn(nullable=false)
     * Assert\NotNull()
     * @Assert\Expression(
     *     "this.typeOf() in ['Afsluiting'] or this.getProject()",
     *     message="Bij een aanmelding is een project verplicht."
     * )
     * @Gedmo\Versioned
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     * @Assert\NotNull
     */
    protected $locatie;

    /**
     * @var BinnenViaOptieKlant
     *
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\BinnenViaOptieKlant", inversedBy="aanmeldingen", )
     * @ORM\JoinColumn(nullable=true, options={"default": 0})
     * @Gedmo\Versioned
     * @Assert\Expression("!this.isAangemeld() or value != null")
     */
    protected $binnenViaOptieKlant;


    public function __construct(Medewerker $medewerker = null)
    {
        $this->medewerker = $medewerker;
        $this->datum = new \DateTime('now');
    }

    public function typeOf(): string
    {
        $className = get_class($this);
        $ref = new \ReflectionClass($className);

        return $ref->getShortName();
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

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

    public function isAangemeld()
    {
        return $this instanceof Aanmelding;
    }

    public function isAfgesloten()
    {
        return $this instanceof Afsluiting;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): MwDossierStatus
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocatie()
    {
        return $this->locatie;
    }

    /**
     * @param mixed $locatie
     * @return Afsluiting
     */
    public function setLocatie($locatie)
    {
        $this->locatie = $locatie;
        return $this;
    }

    /**
     * @return BinnenViaOptieKlant
     */
    public function getBinnenViaOptieKlant(): ?BinnenViaOptieKlant
    {
        return $this->binnenViaOptieKlant;
    }

    /**
     * @param BinnenViaOptieKlant $binnenViaOptieKlant
     * @return Aanmelding
     */
    public function setBinnenViaOptieKlant(BinnenViaOptieKlant $binnenViaOptieKlant): Aanmelding
    {
        $this->binnenViaOptieKlant = $binnenViaOptieKlant;
        return $this;
    }

    /**
     * @PrePersist
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $event
     */
    public function onPrePersist(LifecycleEventArgs $event)
    {
        $this->created = $this->modified = new \DateTime();
    }

    abstract public function validate(ExecutionContextInterface $context, $payload);

    protected function parentValidate(ExecutionContextInterface $context, $payload){

        if($this->klant->getHuidigeMwStatus() instanceof $this)
        {
            $context->buildViolation('Kan geen dubbele aanmelding/afsluiting op een dossier hebben. Huidige dossier status is al een aanmelding of afsluiting.')
                ->atPath('datum')
                ->addViolation();
        }
    }

}
