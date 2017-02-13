<?php

namespace Hi5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hi5_intakes")
 */
class Hi5Intake
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="datum_intake")
     */
    private $intakedatum;

    /**
     */
    private $verblijfstatus;

    /**
     * @ORM\Column(type="string")
     */
    private $postadres;

    /**
     * @ORM\Column(type="string")
     */
    private $postcode;

    /**
     * @ORM\Column(type="string")
     */
    private $woonplaats;

    /**
     * @ORM\Column(type="date", name="verblijf_in_NL_sinds")
     */
    private $verblijfInNlSinds;

    /**
     * @ORM\Column(type="date", name="verblijf_in_amsterdam_sinds")
     */
    private $verblijfInAmsterdamSinds;

    /**
     * @ORM\Column(type="string", name="verslaving_overig")
     */
    private $verslavingOverig;

    /**
     * @ORM\Column(type="string", name="inkomen_overig")
     */
    private $inkomenOverig;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function getId()
    {
        return $this->id;
    }
}
