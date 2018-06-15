<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityNotFoundException;

/**
 * @ORM\Entity
 * @ORM\Table("hs_dienstverleners")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Dienstverlener extends Arbeider implements MemoSubjectInterface, DocumentSubjectInterface
{
    use HulpverlenerTrait, MemoSubjectTrait, DocumentSubjectTrait;

    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\ManyToMany(targetEntity="Klus", mappedBy="dienstverleners")
     * @ORM\OrderBy({"startdatum": "desc"})
     */
    protected $klussen;

    public function __construct(AppKlant $klant = null)
    {
        if ($klant) {
            $this->klant = $klant;
        }

        parent::__construct();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatFormal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(AppKlant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function addKlus(Klus $klus)
    {
        $this->klussen[] = $klus;

        return $this;
    }
}
