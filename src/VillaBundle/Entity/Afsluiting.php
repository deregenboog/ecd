<?php

namespace VillaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluiting extends DossierStatus
{
    /**
     * @var AfsluitredenSlaper
     *
     * @ORM\ManyToOne(targetEntity="AfsluitredenSlaper")
     * @Gedmo\Versioned
     * @Assert\NotNull
     */
    protected $reden;

    public function __toString()
    {
        return sprintf(
            'Afsluiting op %s door %s (%s)',
            $this->datum->format('d-m-Y'),
            $this->medewerker,
            $this->reden,

        );
    }

    public function getReden(): ?AfsluitredenSlaper
    {
        return $this->reden;
    }

    public function setReden(?AfsluitredenSlaper $reden): void
    {
        $this->reden = $reden;
    }



}
