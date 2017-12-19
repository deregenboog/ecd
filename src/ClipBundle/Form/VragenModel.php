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
        foreach ($this->soorten as $soort) {
            $vraag = new Vraag();
            $vraag
                ->setSoort($soort)
                ->setBehandelaar($this->getBehandelaar())
                ->setClient($this->getClient())
                ->setCommunicatiekanaal($this->getCommunicatiekanaal())
                ->setHulpvrager($this->getHulpvrager())
                ->setLeeftijdscategorie($this->getLeeftijdscategorie())
                ->setOmschrijving($this->getOmschrijving())
                ->setStartdatum($this->getStartdatum())
            ;
            $vraag->getContactmoment()->setOpmerking($this->getContactmoment()->getOpmerking());

            $vragen[] = $vraag;
        }

        return $vragen;
    }
}
