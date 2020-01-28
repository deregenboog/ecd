<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Zrm;
use IzBundle\Entity\Intake;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
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

    public function isOngedocumenteerd()
    {
        return $this->intake->isOngedocumenteerd();
    }

    public function setOngedocumenteerd($ongedocumenteerd)
    {
        return $this->intake->setOngedocumenteerd($ongedocumenteerd);
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
      if($this->intake->getIzDeelnemer() instanceof IzKlant)
      {
          return <<<EOF
Interesses, wensen en doelen:


Zingeving:


Daginvulling:


Sociale relaties:


Woon en leefomstandigheden:


Psychisch functioneren/mentale welbevinden/forensisch:


Financiën:


Gevoel van veiligheid:


Seksualiteit en intimiteit:


Eigen indruk:


EOF;

      }elseif($this->intake->getIzDeelnemer() instanceof IzVrijwilliger)
      {
          return <<<EOF
Waarom wil je vrijwilliger worden bij DRG?


Heb je al eerder vrijwilligerswerk gedaan?


Wat is jouw persoonlijke situatie?


Hoe zou je jezelf beschrijven?


Welke talen spreek en versta je?


Rook je?


Welke doelgroep spreekt jou het meeste aan en waarom?


Met welke doelgroep zou je niet willen werken? En waarom?


In welke mate ben jij beschikbaar?


EOF;

      }


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
