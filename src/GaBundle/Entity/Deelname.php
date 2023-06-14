<?php

namespace GaBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="ga_deelnames",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_activiteit_dossier_idx", columns={"activiteit_id", "dossier_id"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Deelname
{
    use IdentifiableTrait;
    use TimestampableTrait;

    public const STATUS_AANWEZIG = 'aanwezig';
    public const STATUS_AFWEZIG = 'afwezig';

    /**
     * @var Activiteit
     *
     * @ORM\ManyToOne(targetEntity="Activiteit", inversedBy="deelnames")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $activiteit;

    /**
     * @var Dossier
     *
     * @ORM\ManyToOne(targetEntity="Dossier", inversedBy="deelnames")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $dossier;

    /**
     * Current state.
     *
     * @var DeelnameStatus
     *
     * @ORM\Column()
     * @Gedmo\Versioned
     */
    protected $status = self::STATUS_AANWEZIG;

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

    public function __construct(Activiteit $activiteit = null, Dossier $dossier = null)
    {
        $this->activiteit = $activiteit;
        $this->dossier = $dossier;
    }

    public function __toString()
    {
        return sprintf('%s aan %s', $this->dossier, $this->activiteit);
    }

    public function getActiviteit()
    {
        return $this->activiteit;
    }

    public function setActiviteit(Activiteit $activiteit)
    {
        $this->activiteit = $activiteit;

        return $this;
    }

    public function getDossier()
    {
        return $this->dossier;
    }

    public function setDossier(Dossier $dossier)
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
