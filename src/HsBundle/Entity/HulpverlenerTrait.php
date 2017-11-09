<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait HulpverlenerTrait
{
    /**
     * @ORM\Column(name="hulpverlener_naam", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $naamHulpverlener;

    /**
     * @ORM\Column(name="hulpverlener_organisatie", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $organisatieHulpverlener;

    /**
     * @ORM\Column(name="hulpverlener_telefoon", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $telefoonHulpverlener;

    /**
     * @ORM\Column(name="hulpverlener_email", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $emailHulpverlener;

    public function getHulpverlener()
    {
        return new Hulpverlener(
            $this->naamHulpverlener,
            $this->organisatieHulpverlener,
            $this->telefoonHulpverlener,
            $this->emailHulpverlener
        );
    }

    public function setHulpverlener(Hulpverlener $hulpverlener)
    {
        $this->naamHulpverlener = $hulpverlener->getNaam();
        $this->organisatieHulpverlener = $hulpverlener->getOrganisatie();
        $this->telefoonHulpverlener = $hulpverlener->getTelefoon();
        $this->emailHulpverlener = $hulpverlener->getEmail();
    }
}
