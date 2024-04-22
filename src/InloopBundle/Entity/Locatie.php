<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Util\DateTimeUtil;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="locaties", indexes={
 *     @ORM\Index(name="id", columns={"id", "naam", "datum_van", "datum_tot"}),
 *     @ORM\Index(name="id_2", columns={"id", "datum_van"}),
 *     @ORM\Index(name="id_3", columns={"id", "datum_van", "datum_tot"}),
 *     @ORM\Index(name="datum_tot", columns={"id", "datum_tot"}),
 *     @ORM\Index(name="datum_van", columns={"datum_van","datum_tot","naam", "id"}),
 *     @ORM\Index(name="naam", columns={"naam", "datum_van","datum_tot","id"})
 * })
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Locatie
{
    use IdentifiableTrait;
    use NameableTrait;
    use TimestampableTrait;

    /**
     * @var Collection<int,LocatieType>
     * @ORM\ManyToMany(targetEntity="LocatieType",inversedBy="locaties")
     */
    private $locatieTypes;

    /**
     * @ORM\Column(name="nachtopvang", type="boolean", options={"default"=0})
     * @Gedmo\Versioned
     */
    private $nachtopvang = false;

    /**
     * @ORM\Column(name="gebruikersruimte", type="boolean", options={"default"=0})
     * @Gedmo\Versioned
     */
    private $gebruikersruimte = false;

    /**
     * @ORM\Column(name="maatschappelijkwerk", type="boolean", nullable=true, options={"default"=0})
     * @Gedmo\Versioned
     */
    private $maatschappelijkWerk = false;

    /**
     * @ORM\Column(name="tbc_check", type="boolean", options={"default"=0})
     * @Gedmo\Versioned
     */
    private $tbcCheck = false;

    /**
     * @ORM\Column(name="wachtlijst", type="integer", nullable=true, options={"default"=0})
     * @Gedmo\Versioned
     */
    private $wachtlijst = 0; //0=geen, 1=normaal via intakes / 2=economisch via verslag

    /**
     * @ORM\Column(name="datum_van", type="date")
     * @Gedmo\Versioned
     */
    private $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $datumTot;

    /**
     * @var Locatietijd[]
     *
     * @ORM\OneToMany(targetEntity="Locatietijd", mappedBy="locatie", cascade={"persist"})
     * @ORM\OrderBy({"dagVanDeWeek": "ASC"})
     */
    private $locatietijden;

    /**
     * @ORM\ManyToMany(targetEntity="Intake", mappedBy="specifiekeLocaties")
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

    /**
     * @deprecated
     */
    public function getTbcCheck()
    {
        return $this->tbcCheck;
    }

    public function isTbcCheck()
    {
        return $this->tbcCheck;
    }

    public function setTbcCheck($tbcCheck)
    {
        $this->tbcCheck = $tbcCheck;

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
     * Get the Locatietijd for a given moment (or now, is no moment is given).
     * Returns null if the Locatie has no Locatietijd for the given moment.
     */
    public function getLocatietijd(?\DateTime $moment = null): ?Locatietijd
    {
        if (!$moment instanceof \DateTime) {
            $moment = new \DateTime();
        }

        foreach ($this->locatietijden as $locatietijd) {
            // skip irrelevant cases
            if (!in_array((int) $moment->format('w'), [$locatietijd->getOpeningsdag(), $locatietijd->getSluitingsdag()])) {
                continue;
            }

            $openingstijd = DateTimeUtil::combine($moment, $locatietijd->getOpeningstijd())
                ->modify(sprintf("-%d minutes", Locatietijd::CORRECTION_MINUTES));
            if ($locatietijd->getOpeningsdag() !== (int) $moment->format('w')) {
                $openingstijd->modify('-1 day');
            }

            $sluitingstijd = DateTimeUtil::combine($moment, $locatietijd->getSluitingstijd())
                ->modify(sprintf("+%d minutes", Locatietijd::CORRECTION_MINUTES));
            if ($locatietijd->getSluitingsdag() !== (int) $moment->format('w')) {
                $sluitingstijd->modify('+1 day');
            }

            if ($moment >= $openingstijd && $moment <= $sluitingstijd) {
                return $locatietijd;
            }
        }

        return null;
    }

    /**
     * @param Locatietijd $locatietijd
     */
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

    public function isOpen(?\DateTime $date = null): bool
    {
        return $this->getLocatietijd($date) instanceof Locatietijd;
    }

    public function isDeletable()
    {
        return false;
    }

    public function isActief(): bool
    {
        $now = new \DateTime("now");

        if ($this->datumTot == null || ($this->datumVan < $now && $this->datumTot > $now)) {
            return true;
        }
        return false;
    }
    /**
     * @return bool
     */
    public function isWachtlijst(): bool
    {
        return ($this->wachtlijst > 0) ? true : false;
    }

    /**
     * @param int $wachtlijst
     * @return Locatie
     */
    public function setWachtlijst($wachtlijst): Locatie
    {
        $this->wachtlijst = $wachtlijst;
        return $this;
    }

    /**
     * @return int
     */
    public function getWachtlijst(): int
    {
        return $this->wachtlijst;
    }

    /**
     * @return Collection
     */
    public function getLocatieTypes(): ?Collection
    {
        return $this->locatieTypes;
    }

    /**
     * @param  $locatieTypes
     */
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
