<?php

namespace OekBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MemoSubjectTrait;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\MemoSubjectInterface;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table("oek_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vrijwilliger implements MemoSubjectInterface, DocumentSubjectInterface
{
    use IdentifiableTrait, TimestampableTrait, RequiredMedewerkerTrait, MemoSubjectTrait, DocumentSubjectTrait;

    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

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
        return true;
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
}
