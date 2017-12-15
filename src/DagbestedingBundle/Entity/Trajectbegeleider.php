<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\Medewerker;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\ActivatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_trajectbegeleiders")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Trajectbegeleider
{
    use IdentifiableTrait, OptionalMedewerkerTrait, ActivatableTrait, TimestampableTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(name="display_name", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $displayName;

    /**
     * @var ArrayCollection|Traject[]
     *
     * @ORM\OneToMany(targetEntity="Traject", mappedBy="begeleider")
     */
    private $trajecten;

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
