<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table("hs_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vrijwilliger extends Arbeider implements MemoSubjectInterface, DocumentSubjectInterface
{
    use HulpverlenerTrait;
    use MemoSubjectTrait;
    use TimestampableTrait;
    use DocumentSubjectTrait;

    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\ManyToMany(targetEntity="Klus", mappedBy="vrijwilligers")
     * @ORM\OrderBy({"startdatum": "desc"})
     */
    protected $klussen;

    /**
     * @var Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="hs_vrijwilliger_document", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $documenten;


    /**
     * @var Memo[]
     *
     * @ORM\ManyToMany(targetEntity="Memo", cascade={"persist"})
     * @ORM\JoinTable(name="hs_vrijwilliger_memo", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    protected $memos;

    public function __construct(AppVrijwilliger $vrijwilliger = null)
    {
        if ($vrijwilliger) {
            $this->vrijwilliger = $vrijwilliger;
        }

        parent::__construct();
    }

    public function __toString(): string
    {
        try {
            return NameFormatter::formatInformal($this->vrijwilliger);
        } catch (EntityNotFoundException|FatalErrorException $e) {
            return '(verwijderd)';
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVrijwilliger()
    {
        /**
         * Because vrijwilliger can be disabled and this is implemented via an SQL filter. Doctrine just gets an empty object somehow so it seems.
         * Thus, all goes well until the point a field of vrijwilliger is called (in a template for example).
         * But then its too late to catch exceptions.
         * Thus, try a field here (since vrijwilliger is not null) and catch exception. If so, return nul..
         */
        try {
            $this->vrijwilliger->getCreated();
        } catch (\Doctrine\ORM\EntityNotFoundException $e) {
            return null;
        }

        return $this->vrijwilliger;
    }

    public function setVrijwilliger(AppVrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    public function addKlus(Klus $klus)
    {
        $this->klussen[] = $klus;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->actief === false && !$this->uitschrijving
        ) {
            $context->buildViolation('Het is verplicht een uitschrijf datum in te vullen als iemand inactief is.')
                ->atPath('uitschrijving')
                ->addViolation();
        }
    }
}
