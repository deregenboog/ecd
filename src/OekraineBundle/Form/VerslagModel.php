<?php

namespace OekraineBundle\Form;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use OekraineBundle\Entity\Locatie;

use OekraineBundle\Entity\Verslag;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class VerslagModel
{
    private $verslag;
    private $inventarisaties;
    private $data = [];


    public function getVerslag()
    {
        return $this->verslag;
    }

    /**
     * @Assert\NotNull
     */
    public function getDatum()
    {
        return $this->verslag->getDatum();
    }

    public function setDatum(\DateTime $datum)
    {
        return $this->verslag->setDatum($datum);
    }

    /**
     * @Assert\NotBlank
     */
    public function getOpmerking()
    {
        return $this->getVerslag()->getOpmerking();
    }

    public function setOpmerking($opmerking)
    {
        return $this->verslag->setOpmerking($opmerking);
    }

    /**
     * @Assert\NotNull
     */
    public function getKlant()
    {
        return $this->verslag->getKlant();
    }

    public function setKlant(Klant $klant)
    {
        return $this->verslag->setKlant($klant);
    }

    /**
     * @Assert\NotNull
     */
    public function getLocatie()
    {
        return $this->verslag->getLocatie();
    }

    public function setLocatie(Locatie $locatie)
    {
        return $this->verslag->setLocatie($locatie);
    }

    /**
     * @Assert\NotNull
     */
    public function getMedewerker()
    {
        return $this->verslag->getMedewerker();
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        return $this->verslag->setMedewerker($medewerker);
    }

    /**
     * @Assert\NotNull
     */
    public function getAccessType()
    {
        return $this->verslag->getAccess();
    }

    public function setAccessType($accessType)
    {
        $this->verslag->setAccess($accessType);
    }


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

    }

    public function __construct(Verslag $verslag)
    {
        $this->verslag = $verslag;
    }


}
