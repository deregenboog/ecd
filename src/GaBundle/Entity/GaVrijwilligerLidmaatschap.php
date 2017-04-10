<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_groepen_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
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
     */
    private $gaGroep;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vrijwilliger;

    /**
     * @ORM\ManyToOne(targetEntity="GaReden")
     * @ORM\JoinColumn(name="groepsactiviteiten_reden_id", nullable=true)
     */
    private $gaReden;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $einddatum;

    /**
     * @ORM\Column(name="communicatie_email", type="boolean", nullable=true)
     */
    private $communicatieEmail;

    /**
     * @ORM\Column(name="communicatie_telefoon", type="boolean", nullable=true)
     */
    private $communicatieTelefoon;

    /**
     * @ORM\Column(name="communicatie_post", type="boolean", nullable=true)
     */
    private $communicatiePost;

    public function getId()
    {
        return $this->id;
    }
}
