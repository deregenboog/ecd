<?php

namespace OekBundle\Entity;

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
    const STATUS_AANGEMELD = 'Aangemeld';
    const STATUS_GESTART = 'Gestart';
    const STATUS_GEVOLGD = 'Gevolgd';
    const STATUS_AFGEROND = 'Afgerond';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Deelname
     *
     * @ORM\ManyToOne(targetEntity="Deelname", inversedBy="deelnameStatussen")
     * @ORM\JoinColumn(name="oekDeelname_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $deelname;

    /**
     * @ORM\Column(type="date", nullable=false)
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
        $thisClass = new \ReflectionClass(__CLASS__);
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
