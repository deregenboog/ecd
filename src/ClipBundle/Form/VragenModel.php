<?php

namespace ClipBundle\Form;

use ClipBundle\Entity\Vraag;
use Doctrine\Common\Collections\ArrayCollection;

class VragenModel extends Vraag
{
    private $soorten;

    public function __construct()
    {
        $this->soorten = new ArrayCollection();
        parent::__construct();
    }

    public function getSoorten()
    {
        return $this->soorten;
    }

    public function getVragen()
    {
        $vragen = [];
        $cm = null;
        foreach ($this->soorten as $soort) {
            $cm = (null === $cm)? $this->getContactmoment():clone($cm);
            //duplicate each contactmoment otherwise it gets only stored for one vraag.

            $vraag = new Vraag( $cm);
            $vraag
                ->setSoort($soort)
//                ->setContactmoment()
                ->setBehandelaar($this->getBehandelaar())
                ->setClient($this->getClient())
                ->setCommunicatiekanaal($this->getCommunicatiekanaal())
                ->setHulpvrager($this->getHulpvrager())
                ->setLeeftijdscategorie($this->getLeeftijdscategorie())
                ->setOmschrijving($this->getOmschrijving())
                ->setStartdatum($this->getStartdatum())

            ;
          //  $vraag->getContactmoment()->setOpmerking($this->getContactmoment()->getOpmerking());

            $vragen[] = $vraag;
        }

        return $vragen;
    }
}
