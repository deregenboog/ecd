<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_facturen")
 */
class Factuur
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nummer;

    /**
     * @ORM\Column(type="date")
     */
    private $datum;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $betreft;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $bedrag;

    /**
     * @var Klant
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="facturen")
     */
    private $klant;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\ManyToMany(targetEntity="Klus", inversedBy="facturen")
     */
    private $klussen;

    /**
     * @var ArrayCollection|Registratie[]
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="factuur", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $registraties;

    /**
     * @var ArrayCollection|Declaratie[]
     * @ORM\OneToMany(targetEntity="Declaratie", mappedBy="factuur", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $declaraties;

    /**
     * @var ArrayCollection|Betaling[]
     * @ORM\OneToMany(targetEntity="Betaling", mappedBy="factuur")
     */
    private $betalingen;

    public function __construct(Klant $klant, $nummer, $betreft)
    {
        $this->klant = $klant;
        $this->nummer = $nummer;
        $this->betreft = $betreft;

        $this->betalingen = new ArrayCollection();
        $this->declaraties = new ArrayCollection();
        $this->klussen = new ArrayCollection();
        $this->registraties = new ArrayCollection();

        $this->setDatum(new \DateTime());

//         $start = new \DateTime('first day of previous month');
// //         $end = new \DateTime('last day of previous month');
//         $end = new \DateTime('last day of this month');

//         $this->nummer = sprintf(
//             '%d/%d',
//             $klant->getId(),
//             $end->format('ymd')
//         );

//         $this->betreft = sprintf(
//             'Factuurnr: %s van %s t/m %s',
//             $this->nummer,
//             $start->format('d-m-Y'),
//             $end->format('d-m-Y')
//         );

//         $criteria = Criteria::create()
//             ->where(Criteria::expr()->isNull('factuur'))
//             ->andWhere(Criteria::expr()->lte('datum', $end))
//         ;

//         foreach ($klant->getKlussen() as $klus) {
//             foreach ($klus->getDeclaraties()->matching($criteria) as $declaratie) {
//                 $this->declaraties->add($declaratie);
//                 if (!$this->klussen->contains($klus)) {
//                     $this->klussen->add($klus);
//                 }
//             }
//             foreach ($klus->getRegistraties()->matching($criteria) as $registratie) {
//                 $this->registraties->add($registratie);
//                 if (!$this->klussen->contains($klus)) {
//                     $this->klussen->add($klus);
//                 }
//             }
//         }

//         $this->calculate();
    }

    public function __toString()
    {
        return $this->nummer;
    }

    public function isEmpty()
    {
        return count($this->declaraties) === 0
            && count($this->registraties) === 0
            && count($this->betalingen) === 0
            && count($this->klussen) === 0
        ;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNummer()
    {
        return $this->nummer;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getRegistraties()
    {
        return $this->registraties;
    }

    public function addRegistratie(Registratie $registratie)
    {
        $this->registraties[] = $registratie;
        $registratie->setFactuur($this);

        if (!$this->klussen->contains($registratie->getKlus())) {
            $this->klussen->add($registratie->getKlus());
        }

        return $this;
    }

    public function getDeclaraties()
    {
        return $this->declaraties;
    }

    private function addDeclaratie(Declaratie $declaratie)
    {
        $this->declaraties[] = $declaratie;
        $declaratie->setFactuur($this);

        if (!$this->klussen->contains($declaratie->getKlus())) {
            $this->klussen->add($declaratie->getKlus());
        }

        return $this;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }

    public function setBedrag($bedrag)
    {
        $this->bedrag = $bedrag;

        return $this;
    }

    public function isDeletable()
    {
        return count($this->betalingen) === 0;
    }

    public function getBetalingen()
    {
        return $this->betalingen;
    }

    public function getBetaald()
    {
        $betaald = 0.0;
        foreach ($this->betalingen as $betaling) {
            $betaald += $betaling->getBedrag();
        }

        return $betaald;
    }

    public function getSaldo()
    {
        return $this->bedrag - $this->getBetaald();
    }

    public function getBetreft()
    {
        return $this->betreft;
    }

}
