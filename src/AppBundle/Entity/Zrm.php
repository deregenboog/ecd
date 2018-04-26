<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\IdentifiableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="zrm_reports")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string", length=5)
 * @ORM\DiscriminatorMap({"zrmv1" = "ZrmV1", "zrmv2" = "ZrmV2"})
 * @Gedmo\Loggable
 */
abstract class Zrm
{
    use IdentifiableTrait, TimestampableTrait;

    CONST ZRM_VERSIONS = [
        ZrmV2::class => '2017-10-05',
        ZrmV1::class => '2010-01-01',
    ];

    /**
     * @ORM\ManyToOne(targetEntity="Klant")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @ORM\Column(name="model", type="string", nullable=false)
     * @Gedmo\Versioned
     */
    protected $model;

    /**
     * @ORM\Column(name="foreign_key", type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    protected $foreignKey;

    /**
     * @ORM\Column(name="request_module", type="string", nullable=false)
     * @Gedmo\Versioned
     */
    protected $requestModule;

    /**
     * @return Zrm
     */
    public static function create(\DateTime $datum = null)
    {
        if (!$datum) {
            $datum = new \DateTime();
        }

        foreach (self::ZRM_VERSIONS as $model => $date) {
            if ($datum >= new \DateTime($date)) {
                return new $model();
            }
        }
    }

    public function __construct(Klant $klant = null)
    {
        $this->klant = $klant;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getRequestModule()
    {
        return $this->requestModule;
    }

    public function setRequestModule($requestModule)
    {
        $this->requestModule = $requestModule;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    public function setForeignKey($foreignKey)
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }
}
