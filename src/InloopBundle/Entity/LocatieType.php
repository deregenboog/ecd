<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="locatie_type")
 * @Gedmo\Loggable
 */
class LocatieType
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;


    /**
     * @var ArrayCollection|Locatie[]
     * @ORM\ManyToMany(targetEntity="Locatie", mappedBy="locatieTypes")
     */
    protected $locaties;

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

}
