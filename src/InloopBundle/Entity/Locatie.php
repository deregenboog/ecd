<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="locaties", indexes={
 *
 *     @ORM\Index(name="id", columns={"id", "naam", "datum_van", "datum_tot"}),
 *     @ORM\Index(name="id_2", columns={"id", "datum_van"}),
 *     @ORM\Index(name="id_3", columns={"id", "datum_van", "datum_tot"}),
 *     @ORM\Index(name="datum_tot", columns={"id", "datum_tot"}),
 *     @ORM\Index(name="datum_van", columns={"datum_van","datum_tot","naam", "id"}),
 *     @ORM\Index(name="naam", columns={"naam", "datum_van","datum_tot","id"})
 * })
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Locatie
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;

    /**
     * @var Collection<int,LocatieType>
     *
     * @ORM\ManyToMany(targetEntity="LocatieType",inversedBy="locaties")
     */
    private $locatieTypes;

    /**
     * @ORM\Column(name="nachtopvang", type="boolean", options={"default"=0})
     *
     * @Gedmo\Versioned
     */
    private $nachtopvang = false;

    /**
     * @ORM\Column(name="gebruikersruimte", type="boolean", options={"default"=0})
     *
     * @Gedmo\Versioned
     */
    private $gebruikersruimte = false;

    /**
     * @ORM\Column(name="maatschappelijkwerk", type="boolean", nullable=true, options={"default"=0})
     *
     * @Gedmo\Versioned
     */
    private $maatschappelijkWerk = false;

    /**
     * @ORM\Column(name="tbc_check", type="boolean", options={"default"=0})
     *
     * @Gedmo\Versioned
     */
    private $tbcCheck = false;

    /**
     * @ORM\Column(name="wachtlijst", type="integer", nullable=true, options={"default"=0})
     *
     * @Gedmo\Versioned
     */
    private $wachtlijst = 0; // 0=geen, 1=normaal via intakes / 2=economisch via verslag

    /**
     * @ORM\Column(name="datum_van", type="date")
     *
     * @Gedmo\Versioned
     */
    private $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $datumTot;

    /**
     * @var Locatietijd[]
     *
     * @ORM\OneToMany(targetEntity="Locatietijd", mappedBy="locatie", cascade={"persist"})
     *
     * @ORM\OrderBy({"dagVanDeWeek": "ASC"})
     */
    private $locatietijden;

    private $openingTimeCorrection = 30 * 60; // 30 minutes. correctie op de openingstijd. Openingstijd gaat een half uur eerder al open... en een half uur langer door.

    /**
     * @ORM\ManyToMany(targetEntity="Intake", mappedBy="specifiekeLocaties")
     *
     * @var Collection<int, Intake>
     */
    private Collection $accessIntakes;

    public function __construct()
    {
        $this->locatieTypes = new ArrayCollection();
        $this->locatietijden = new ArrayCollection();
        $this->accessIntakes = new ArrayCollection();
    }

    /**
     * @deprecated
     */
    public function getNachtopvang()
    {
        return $this->nachtopvang;
    }

    public function isNachtopvang()
    {
        return $this->nachtopvang;
    }

    public function setNachtopvang($nachtopvang)
    {
        $this->nachtopvang = $nachtopvang;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function isGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function setGebruikersruimte($gebruikersruimte)
    {
        $this->gebruikersruimte = $gebruikersruimte;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getMaatschappelijkWerk()
    {
        return $this->maatschappelijkWerk;
    }

    public function isMaatschappelijkWerk()
    {
        return $this->maatschappelijkWerk;
    }

    public function setMaatschappelijkWerk($maatschappelijkWerk)
    {
        $this->maatschappelijkWerk = $maatschappelijkWerk;

        return $this;
    }

    public function getDatumVan()
    {
        return $this->datumVan;
    }

    public function setDatumVan(\DateTime $datumVan)
    {
        $this->datumVan = $datumVan;

        return $this;
    }

    public function getDatumTot()
    {
        return $this->datumTot;
    }

    public function setDatumTot(?\DateTime $datumTot)
    {
        $this->datumTot = $datumTot;

        return $this;
    }

    /**
     * @return Locatietijd[]
     */
    public function getLocatietijden()
    {
        return $this->locatietijden;
    }

    /**
     * Off by 0. zondag = 0. zaterdag - 6.
     * Gets locatietijden for day of week.
     */
    public function getLocatietijd(int $dayOfWeek): ?Locatietijd
    {
        //        $dayOfWeek = (int) $dayOfWeek;
        while ($dayOfWeek >= 7) {
            $dayOfWeek = $dayOfWeek % 7;
        }

        foreach ($this->locatietijden as $locatietijd) {
            if ($dayOfWeek === $locatietijd->getDagVanDeWeek()) {
                return $locatietijd;
            }
        }

        return null;
    }

    public function addLocatietijd(Locatietijd $locatietijd)
    {
        $this->locatietijden[] = $locatietijd;
        $locatietijd->setLocatie($this);

        return $this;
    }

    /**
     * @param Locatietijd[] $locatietijden
     */
    public function setLocatietijden($locatietijden)
    {
        $this->locatietijden = $locatietijden;

        return $this;
    }

    public function isOpen(?\DateTime $date = null)
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }
        $openingstijd = new \DateTime();
        $sluitingstijd = new \DateTime();
        $debug['message'] = null;
        $locatietijd = $this->getLocatietijd($date->format('w'));

        if ($locatietijd) {
            $openingstijd = (clone $locatietijd->getOpeningstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify("-{$this->openingTimeCorrection} seconds");
            $sluitingstijd = (clone $locatietijd->getSluitingstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify("+{$this->openingTimeCorrection} seconds");

            /*
             * Dus stel dat de sluitingstijd 23:59 was en we tellen er 30 minuten bij op
             * (hierboven)
             * Dan kan het dus zijn dat openingstijd groter is dan de sluitingstijd. bv:
             * openingstijd: 00:00
             * sluitingstijd: 23:59
             *
             * - en +30 min =
             * opening 23:30
             * sluiting 00:29,
             * dan dus + 1 dag want anders loopt het mis...
             */
            if ($openingstijd > $sluitingstijd) {
                $sluitingstijd->modify('+1 day');
            }

            if ($date >= $openingstijd && $date <= $sluitingstijd) {
                return true;
            }

            $debug['message'] = 'Er gaat iets mis in het berekenen van de openingstijd. Dag van de week: '."\n\n".$date->format('w');
            $debug['date'] = $date;
            $debug['openingstijd'] = $openingstijd;
            $debug['sluitingstijd'] = $sluitingstijd;

            //            return $debug;
        }

        /**
         * Ok, nu kan het dus zijn dat ie nog niet open is op de dag dat wordt gevraagd.
         * Dat kan komen, doordat de sluitingstijd bv. na middernacht is. en dus eigenlijk de volgende dag.
         * Op die 'volgende dag' is er dan bv geen locatietijd aanwezig. Bv nachtopvang
         * die gaat open om 1500 en sluit om 0200
         * maar 0200 is dan de volgende dag.
         *
         * dus we doen dan net, alsof het gister was... dat is die -1.
         */
        $locatietijd = $this->getLocatietijd($date->format('w') - 1);
        if ($locatietijd && $locatietijd->getOpeningstijd() > $locatietijd->getSluitingstijd()) {
            $openingstijd = (clone $locatietijd->getOpeningstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify('-1 day')
                ->modify("-{$this->openingTimeCorrection} seconds");
            $sluitingstijd = (clone $locatietijd->getSluitingstijd())
                ->setDate($date->format('Y'), $date->format('m'), $date->format('d'))
                ->modify('-1 day')
                ->modify("+{$this->openingTimeCorrection} seconds");
            if ($openingstijd > $sluitingstijd) {
                $sluitingstijd->modify('+1 day');
            }
            if ($date >= $openingstijd && $date <= $sluitingstijd) {
                return true;
            }

            // geen $locatietijd, bv omdat er geen openingstijden zijn op die dag.
            $debug['message'] .= '\n\nKan geen geschikte $locatietijd vinden voor dag van de week'."\n\n".$date->format('w');
            $debug['message'] .= "\nOok de sluitingsdatum en dag terugzetten geeft niet het gewenste resultaat (in geval van sluiting na middernacht scenario)";
            $debug['message'] .= "\n\nDate (nu): ".$date->format('d-m-Y H:i:s');
            $debug['message'] .= "\nOpeningstijd: ".$openingstijd->format('d-m-Y H:i:s');
            $debug['message'] .= "\nSluitingstijd: ".$sluitingstijd->format('d-m-Y H:i:s');

            return $debug;

            return false;
        }
    }

    public function getClosingTimeByDayOfWeek($dayOfWeek)
    {
        foreach ($this->locatietijden as $locatietijd) {
            if ($dayOfWeek == $locatietijd->getDagVanDeWeek()) {
                return $locatietijd->getSluitingstijd();
            }
        }
    }

    public function isDeletable()
    {
        return false;
    }

    public function isActief(): bool
    {
        $now = new \DateTime('now');

        if (null == $this->datumTot || ($this->datumVan < $now && $this->datumTot > $now)) {
            return true;
        }

        return false;
    }

    public function isWachtlijst(): bool
    {
        return ($this->wachtlijst > 0) ? true : false;
    }

    /**
     * @param int $wachtlijst
     */
    public function setWachtlijst($wachtlijst): Locatie
    {
        $this->wachtlijst = $wachtlijst;

        return $this;
    }

    public function getWachtlijst(): int
    {
        return $this->wachtlijst;
    }

    public function getLocatieTypes(): ?Collection
    {
        return $this->locatieTypes;
    }

    public function setLocatieTypes($locatieTypes): void
    {
        $this->locatieTypes = $locatieTypes;
    }

    public function hasLocatieType(LocatieType $locatieType): bool
    {
        $r = false;
        //        $erOpUit = $this->entityManager->getRepository(ErOpUitBundle\Entity\Klant::class)
        //            ->findOneBy(['klant' => $persoon]);
        $r = $this->locatieTypes->contains($locatieType);

        return $r;
    }
}
