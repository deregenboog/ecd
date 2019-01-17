<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Zrm;
use IzBundle\Entity\Intake;
use IzBundle\Entity\Verslag;

class IntakeModel
{
    /**
     * @var Intake
     */
    private $intake;

    public function __construct(Intake $intake)
    {
        $this->intake = $intake;
    }

    public function getIntake()
    {
        return $this->intake;
    }

    public function getIntakedatum()
    {
        return $this->intake->getIntakedatum();
    }

    public function setIntakedatum(\DateTime $datum)
    {
        return $this->intake->setIntakedatum($datum);
    }

    public function getMedewerker()
    {
        return $this->intake->getMedewerker();
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        return $this->intake->setMedewerker($medewerker);
    }

    public function isGezinMetKinderen()
    {
        return $this->intake->isGezinMetKinderen();
    }

    public function setGezinMetKinderen($gezinMetKinderen)
    {
        return $this->intake->setGezinMetKinderen($gezinMetKinderen);
    }

    public function isStagiair()
    {
        return $this->intake->isStagiair();
    }

    public function setStagiair($stagiair)
    {
        return $this->intake->setStagiair($stagiair);
    }

    public function getVerslag()
    {
        return null;
    }

    public function setVerslag($opmerking)
    {
        $verslag = new Verslag();
        $verslag
            ->setMedewerker($this->intake->getMedewerker())
            ->setIzDeelnemer($this->intake->getIzDeelnemer())
            ->setOpmerking($opmerking)
        ;

        return $this->intake->getIzDeelnemer()->addVerslag($verslag);
    }

    public function getZrm()
    {
        return $this->intake->getZrm();
    }

    public function setZrm(Zrm $zrm)
    {
        $this->intake->setZrm($zrm);

        return $this;
    }
}
