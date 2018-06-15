<?php

namespace GaBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_groepen_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class GaVrijwilligerLidmaatschap
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GaGroep", inversedBy="gaVrijwilligerLeden")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $gaGroep;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $vrijwilliger;

    /**
     * @ORM\ManyToOne(targetEntity="GaReden")
     * @ORM\JoinColumn(name="groepsactiviteiten_reden_id", nullable=true)
     * @Gedmo\Versioned
     */
    private $gaReden;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @ORM\Column(name="communicatie_email", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $communicatieEmail;

    /**
     * @ORM\Column(name="communicatie_telefoon", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $communicatieTelefoon;

    /**
     * @ORM\Column(name="communicatie_post", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $communicatiePost;

    public function getId()
    {
        return $this->id;
    }
}
