<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_deelnames")
 * @ORM\HasLifecycleCallbacks
 */
class OekDeelname
{
    use TimestampableTrait;

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
     * @var OekTraining
     *
     * @ORM\ManyToOne(targetEntity="OekTraining", inversedBy="oekDeelnames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $oekTraining;

    /**
     * @var OekKlant
     *
     * @ORM\ManyToOne(targetEntity="OekKlant", inversedBy="oekDeelnames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $oekKlant;

    /**
     * @var string
     * @ORM\Column
     */
    private $status = self::STATUS_AANGEMELD;

    /**
     * Get all statuses defined in this class' constants
     *
     * @return array
     */
    public static function getAllStatuses()
    {
        $thisClass = new \ReflectionClass(__CLASS__);
        $constants = $thisClass->getConstants();

        return array_filter($constants, function($key) {
            return strpos($key, 'STATUS') === 0;
        }, ARRAY_FILTER_USE_KEY);
    }

    public function __construct(OekTraining $oekTraining = null, OekKlant $oekKlant = null)
    {
        $this->oekTraining = $oekTraining;
        $this->oekKlant = $oekKlant;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOekTraining()
    {
        return $this->oekTraining;
    }

    public function setOekTraining(OekTraining $oekTraining)
    {
        $this->oekTraining = $oekTraining;

        return $this;
    }

    public function getOekKlant()
    {
        return $this->oekKlant;
    }

    public function setOekKlant(OekKlant $oekKlant)
    {
        $this->oekKlant = $oekKlant;

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
        return $this->status === self::STATUS_AANGEMELD;
    }
}
