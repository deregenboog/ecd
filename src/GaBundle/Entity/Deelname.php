<?php

namespace GaBundle\Entity;

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
    use TimestampableTrait;

    const STATUS_AANWEZIG = 'aanwezig';
    const STATUS_AFWEZIG = 'afwezig';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

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

    public function __construct(Activiteit $activiteit = null, Dossier $dossier = null)
    {
        $this->activiteit = $activiteit;
        $this->dossier = $dossier;
    }

    public function __toString()
    {
        return sprintf('%s aan %s', $this->dossier, $this->activiteit);
    }

    public function getId()
    {
        return $this->id;
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
