<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostcodegebiedRepository")
 *
 * @ORM\Table(name="postcodegebieden")
 *
 * @Gedmo\Loggable
 */
class Postcodegebied
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Gedmo\Versioned
     */
    private $postcodegebied;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\Versioned
     */
    private $van;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\Versioned
     */
    private $tot;

    public function __toString()
    {
        return $this->postcodegebied;
    }

    public function getPostcodegebied()
    {
        return $this->postcodegebied;
    }
}
