<?php

namespace MwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="MwBundle\Repository\DossierStatusRepository")
 * @ORM\Table(name="mw_binnen_via")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({
 *     "BinnenViaOptieVW" = "BinnenViaOptieVrijwilliger",
 *     "BinnenViaOptieKlant" = "BinnenViaOptieKlant"
 * })
 * @Gedmo\Loggable
 */
abstract class BinnenVia
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function isDeletable()
    {
        return false;
    }
}
