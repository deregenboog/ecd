<?php

namespace VillaBundle\Entity;

use AppBundle\Model\DossierStatusInterface;
use AppBundle\Model\DossierStatusTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Util\FormTypeMapper;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="villa_dossier_statussen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "VillaAanmelding" = "Aanmelding",
 *     "VillaAfsluiting" = "Afsluiting"
 * })
 * @Gedmo\Loggable
 */
abstract class DossierStatus implements DossierStatusInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;
    use DossierStatusTrait;

    /**
     * @var Slaper
     *
     * @ORM\ManyToOne(targetEntity="Slaper", inversedBy="dossierStatussen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $slaper;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $aangemeldVia;

    public function __construct()
    {
        $this->initializeDossierStatusTrait();
    }


    public function getSlaper()
    {
        return $this->slaper;
    }

    public function setSlaper(?Slaper $slaper)
    {
        $this->slaper = $slaper;
        $this->entity = $slaper;

        return $this;
    }

    /**
     * @return string
     */
    public function getAangemeldVia(): ?string
    {
        return $this->aangemeldVia;
    }

    /**
     * @param string $aangemeldVia
     */
    public function setAangemeldVia(string $aangemeldVia): void
    {
        $this->aangemeldVia = $aangemeldVia;
    }

    public function isAfgesloten(): bool
    {
        if(get_class($this) == Afsluiting::class) return true;
        return false;
    }

    public function isAangemeld(): bool
    {
        return !$this->isAfgesloten();
    }


    public function mapClasses(): void
    {
        $this->openClass = Aanmelding::class;
        $this->closedClass = Afsluiting::class;
        $this->entityClass = Slaper::class;
    }

    public function setEntity($entity): void
    {
        $this->entity = $entity;
        $this->slaper = $entity;
    }

    public function getFormType(): string
    {

        return FormTypeMapper::mapEntityToFQCNFormType(get_class($this));
    }

}