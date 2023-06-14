<?php

namespace OekBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Model\ActivatableInterface;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\MemoInterface;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MemoSubjectInterface;
use AppBundle\Model\MemoSubjectTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table("oek_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vrijwilliger implements MemoSubjectInterface, DocumentSubjectInterface, ActivatableInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;
    use MemoSubjectTrait;
    use DocumentSubjectTrait;
    use ActivatableTrait;
    use NotDeletableTrait;

    public const STATUS_ACTIEF = 1;
    public const STATUS_VERWIJDERD = 0;

    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var MemoInterface[]
     *
     * @ORM\ManyToMany(targetEntity="OekBundle\Entity\Memo", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    protected $memos;

    /**
     *
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluitdatum;

    /**
     * @var DocumentInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist","remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true, onDelete="RESTRICT")})
     */
    protected $documenten;

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

    public function __construct(AppVrijwilliger $vrijwilliger = null)
    {
        if ($vrijwilliger) {
            $this->vrijwilliger = $vrijwilliger;
        }
    }

    public function __toString()
    {
        return (string) $this->vrijwilliger;
    }

//    public function isDeletable()
//    {
//        return self::STATUS_ACTIEF == $this->getActief();
//    }

    public function getId()
    {
        return $this->id;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(AppVrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActief(): bool
    {
        return $this->actief;
    }

    /**
     * @param bool $status
     */
    public function setActief(bool $status): void
    {
        $this->actief = $status;
    }

    /**
     * @return mixed
     */
    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    /**
     * @param mixed $afsluitdatum
     * @return Vrijwilliger
     */
    public function setAfsluitdatum($afsluitdatum)
    {
        $this->afsluitdatum = $afsluitdatum;
        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->actief === false ?? $this->afsluitddatum
        ) {
            $context->buildViolation('Het is verplicht een afsluitdatum in te vullen als iemand inactief is.')
                ->atPath('afsluitdatum')
                ->addViolation();
        }
    }
}
