<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="dagbesteding_trajectcoaches")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Trajectcoach
{
    use IdentifiableTrait;
    use OptionalMedewerkerTrait;
    use ActivatableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(name="display_name", type="string", length=255, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $displayName;

    /**
     * @var ArrayCollection|Traject[]
     *
     * @ORM\OneToMany(targetEntity="Traject", mappedBy="trajectcoach")
     */
    private $trajecten;

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

    public function __construct()
    {
        $this->trajecten = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->displayName;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;
        $this->setDisplayName();

        return $this;
    }

    private function setDisplayName()
    {
        $this->displayName = $this->medewerker ? (string) $this->medewerker : $this->naam;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;
        $this->setDisplayName();

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->trajecten);
    }

    public function getTrajecten()
    {
        return $this->trajecten;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (!$this->medewerker && !$this->naam) {
            $context->buildViolation('Selecteer een medewerker of geef een naam op.')
                ->addViolation();
        }

        if ($this->medewerker && $this->naam) {
            $context->buildViolation('Geef geen naam op als je ook een medewerker selecteert.')
                ->addViolation();
        }
    }
}
