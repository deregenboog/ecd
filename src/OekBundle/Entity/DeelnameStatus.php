<?php

namespace OekBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_deelname_statussen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class DeelnameStatus
{
    use TimestampableTrait;

    public const STATUS_AANGEMELD = 'Aangemeld';
    public const STATUS_GESTART = 'Gestart';
    public const STATUS_GEVOLGD = 'Gevolgd';
    public const STATUS_AFGEROND = 'Afgerond';
    public const STATUS_VERWIJDERD = 'Verwijderd';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Deelname
     *
     * @ORM\ManyToOne(targetEntity="Deelname", cascade={"persist","remove"}, inversedBy="deelnameStatussen")
     * @ORM\JoinColumn(name="oekDeelname_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $deelname;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @var string
     * @ORM\Column
     * @Gedmo\Versioned
     */
    private $status = self::STATUS_AANGEMELD;

    public function __construct(Deelname $deelname)
    {
        $this->datum = new \DateTime();
        $this->deelname = $deelname;
    }

    /**
     * Get all statuses defined in this class' constants.
     *
     * @return array
     */
    public static function getAllStatuses()
    {
        $thisClass = new \ReflectionClass(self::class);
        $constants = $thisClass->getConstants();

        return array_filter($constants, function ($key) {
            return 0 === strpos($key, 'STATUS');
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getId()
    {
        return $this->id;
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

    public function getDatum()
    {
        return $this->datum;
    }

    public function getDeelname()
    {
        return $this->deelname;
    }
}
