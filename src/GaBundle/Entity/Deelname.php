<?php

namespace GaBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use OekBundle\Entity\DeelnameStatus;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\MappedSuperclass
 * @Gedmo\Loggable
 */
class Deelname
{
    use TimestampableTrait;

    const STATUS_AANWEZIG = 'Aanwezig';
    const STATUS_AFWEZIG = 'Afwezig';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Current state.
     *
     * @var DeelnameStatus
     *
     * @ORM\Column(name="afmeld_status")
     * @Gedmo\Versioned
     */
    protected $status = self::STATUS_AANWEZIG;

    public function __construct(Activiteit $activiteit = null)
    {
        $this->activiteit = $activiteit;
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
