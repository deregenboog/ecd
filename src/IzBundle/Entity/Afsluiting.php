<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_afsluitingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluiting
{
    const DOELGROEP_KLANT = 1;
    const DOELGROEP_VRIJWILLIGER = 2;
    const DOELGROEPEN_LABELS = [
        self::DOELGROEP_KLANT => 'Deelnemers',
        self::DOELGROEP_VRIJWILLIGER => 'Vrijwilligers',
    ];

    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var bool
     *
     * @ORM\Column(name="`active`", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $actief = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $modified;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    protected $doelgroepen = self::DOELGROEP_KLANT + self::DOELGROEP_VRIJWILLIGER;

    public function getDoelgroepen(): array
    {
        $doelgroep = [];
        foreach (array_keys(self::DOELGROEPEN_LABELS) as $bit) {
            if ($this->doelgroepen & $bit) {
                $doelgroep[] = $bit;
            }
        }

        return $doelgroep;
    }

    public function setDoelgroepen(array $doelgroepen): self
    {
        $this->doelgroepen = 0;
        foreach ($doelgroepen as $doelgroep) {
            $this->doelgroepen += $doelgroep;
        }

        return $this;
    }
}
