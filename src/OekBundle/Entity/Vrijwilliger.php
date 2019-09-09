<?php

namespace OekBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MemoSubjectInterface;
use AppBundle\Model\MemoSubjectTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table("oek_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vrijwilliger implements MemoSubjectInterface, DocumentSubjectInterface
{
    use IdentifiableTrait, TimestampableTrait, RequiredMedewerkerTrait, MemoSubjectTrait, DocumentSubjectTrait;

    const STATUS_ACTIEF = 1;
    const STATUS_VERWIJDERD = 0;

    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;


    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    protected $actief = self::STATUS_ACTIEF;


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

    public function isDeletable()
    {
        return self::STATUS_ACTIEF == $this->getActief();
    }

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


}
