<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_deelname_statussen")
 * @ORM\HasLifecycleCallbacks
 */
class OekDeelnameStatus
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
     * @var OekKlant
     *
     * @ORM\ManyToOne(targetEntity="OekDeelname")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $oekDeelname;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $datum;

    /**
     * @var string
     * @ORM\Column
     */
    private $status = self::STATUS_AANGEMELD;

    public function __construct(OekDeelname $oekDeelname)
    {
        $this->datum = new \DateTime();
        $this->oekDeelname = $oekDeelname;
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
            return strpos($key, 'STATUS') === 0;
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

    public function getOekDeelname()
    {
        return $this->oekDeelname;
    }
}
