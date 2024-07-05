<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table("hs_dienstverleners")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Dienstverlener extends Arbeider implements MemoSubjectInterface, DocumentSubjectInterface
{
    use HulpverlenerTrait;
    use MemoSubjectTrait;
    use DocumentSubjectTrait;
    use TimestampableTrait;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @var ArrayCollection|Klus[]
     *
     * @ORM\ManyToMany(targetEntity="Klus", mappedBy="dienstverleners")
     *
     * @ORM\OrderBy({"startdatum": "desc"})
     */
    protected $klussen;

    /**
     * @var Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     *
     * @ORM\JoinTable(name="hs_dienstverlener_document", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $documenten;

    /**
     * @var Memo[]
     *
     * @ORM\ManyToMany(targetEntity="Memo", cascade={"persist"})
     *
     * @ORM\JoinTable(name="hs_dienstverlener_memo", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     *
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    protected $memos;

    public function __construct(?AppKlant $klant = null)
    {
        if ($klant) {
            $this->klant = $klant;
        }

        parent::__construct();
    }

    public function __toString(): string
    {
        try {
            // return NameFormatter::formatFormal($this->klant);
            return NameFormatter::formatInformal($this->klant);
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
