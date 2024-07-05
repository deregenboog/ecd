<?php

namespace ErOpUitBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="eropuit_vrijwilligers")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Vrijwilliger
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use NotDeletableTrait;

    /**
     * @var AppVrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $vrijwilliger;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    private $inschrijfdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $uitschrijfdatum;

    /**
     * @var Uitschrijfreden
     *
     * @ORM\ManyToOne(targetEntity="Uitschrijfreden")
     */
    private $uitschrijfreden;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $communicatieEmail = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $communicatieTelefoon = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $communicatiePost = true;

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

    public function __construct(?AppVrijwilliger $vrijwilliger = null)
    {
        if ($vrijwilliger) {
            $this->vrijwilliger = $vrijwilliger;
        }

        $this->inschrijfdatum = new \DateTime('today');
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->vrijwilliger);
        } catch (EntityNotFoundException $e) {
            return '';
        }
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

    public function reopen()
    {
        $this->inschrijfdatum = new \DateTime();
        $this->uitschrijfdatum = null;
        $this->uitschrijfreden = null;

        return $this;
    }

    public function close()
    {
        $this->uitschrijfdatum = new \DateTime();

        return $this;
    }

    public function getInschrijfdatum()
    {
        return $this->inschrijfdatum;
    }

    public function setInschrijfdatum(\DateTime $inschrijfdatum)
    {
        $this->inschrijfdatum = $inschrijfdatum;

        return $this;
    }

    public function getUitschrijfdatum()
    {
        return $this->uitschrijfdatum;
    }

    public function setUitschrijfdatum(\DateTime $uitschrijfdatum)
    {
        $this->uitschrijfdatum = $uitschrijfdatum;

        return $this;
    }

    public function getUitschrijfreden()
    {
        return $this->uitschrijfreden;
    }

    public function setUitschrijfreden(Uitschrijfreden $uitschrijfreden)
    {
        $this->uitschrijfreden = $uitschrijfreden;

        return $this;
    }

    public function isCommunicatieEmail()
    {
        return $this->communicatieEmail;
    }

    public function setCommunicatieEmail($communicatieEmail)
    {
        $this->communicatieEmail = $communicatieEmail;

        return $this;
    }

    public function isCommunicatiePost()
    {
        return $this->communicatiePost;
    }

    public function setCommunicatiePost($communicatiePost)
    {
        $this->communicatiePost = $communicatiePost;

        return $this;
    }

    public function isCommunicatieTelefoon()
    {
        return $this->communicatieTelefoon;
    }

    public function setCommunicatieTelefoon($communicatieTelefoon)
    {
        $this->communicatieTelefoon = $communicatieTelefoon;

        return $this;
    }

    public function isUitgeschreven()
    {
        return $this->uitschrijfdatum instanceof \DateTime
            && $this->uitschrijfdatum <= new \DateTime('today');
    }

    public function isDeletable()
    {
        return false;
    }
}
