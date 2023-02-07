<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use InloopBundle\Service\LocatieDao;
use InloopBundle\Service\LocatieDaoInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="InloopBundle\Repository\SchorsingRepository")
 * @ORM\Table(name="schorsingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Schorsing
{
    use TimestampableTrait;

    public const DOELWIT_MEDEWERKER = 1;
    public const DOELWIT_STAGIAIR = 2;
    public const DOELWIT_VRIJWILLIGER = 3;
    public const DOELWITTEN = [
        self::DOELWIT_MEDEWERKER => 'medewerker',
        self::DOELWIT_STAGIAIR => 'stagiair',
        self::DOELWIT_VRIJWILLIGER => 'vrijwilliger',
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="datum_van", type="date", nullable=false)
     * @Assert\NotNull
     */
    private $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date", nullable=false)
     * @Assert\NotNull
     */
    private $datumTot;

    /**
     * @ORM\Column(name="remark", type="text", nullable=true)
     */
    private $opmerking;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bijzonderheden;

    /**
     * @ORM\Column(name="overig_reden", nullable=true)
     */
    private $redenOverig;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $gezien = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $terugkeergesprekGehad = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $aangifte = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $nazorg = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"DEFAULT 0"})
     */
    private $agressie = false;

    /**
     * @ORM\Column(name="aggressie_doelwit", nullable=true)
     */
    private $doelwitAgressie1;

    /**
     * @ORM\Column(name="aggressie_doelwit2", nullable=true)
     */
    private $doelwitAgressie2;

    /**
     * @ORM\Column(name="aggressie_doelwit3", nullable=true)
     */
    private $doelwitAgressie3;

    /**
     * @ORM\Column(name="aggressie_doelwit4", nullable=true)
     */
    private $doelwitAgressie4;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie1;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker2", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie2;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker3", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie3;

    /**
     * @ORM\Column(name="aggressie_tegen_medewerker4", type="integer", length=4, nullable=true)
     */
    private $typeDoelwitAgressie4;

    /**
     * @ORM\Column(length=100, nullable=true)
     */
    private $locatiehoofd;

    /**
     * @deprecated
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $locatie;

    /**
     * @ORM\ManyToMany(targetEntity="Locatie")
     * @ORM\JoinTable(name="schorsing_locatie")
     * @Assert\Count(min=1, minMessage="Selecteer tenminste één locatie")
     */
    private $locaties;

    /**
     * @ORM\ManyToMany(targetEntity="SchorsingReden")
     * @ORM\JoinTable(
     *     name="schorsingen_redenen",
     *     joinColumns={@ORM\JoinColumn(name="schorsing_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="reden_id")}
     * ).
     * @Assert\Count(min=1, minMessage="Selecteer tenminste één reden")
     */
    private $redenen;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="schorsingen")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $klant;

    public function __construct(Klant $klant = null)
    {
        $this->setKlant($klant);
        $this->setDatumVan(new \DateTime());
        $this->locaties = new ArrayCollection();
        $this->redenen = new ArrayCollection();
    }


    public function __toString()
    {
        return sprintf('%s (%s t/m %s)', $this->klant, $this->datumVan->format('d-m-Y'), $this->datumTot->format('d-m-Y'));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDatumVan()
    {
        return $this->datumVan;
    }

    public function setDatumVan($datumVan)
    {
        $this->datumVan = $datumVan;

        return $this;
    }

    public function getDatumTot()
    {
        return $this->datumTot;
    }

    public function setDatumTot($datumTot)
    {
        $this->datumTot = $datumTot;

        return $this;
    }

    public function getOpmerking()
    {
        return utf8_decode($this->opmerking);
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = utf8_encode($opmerking);

        return $this;
    }

    public function getBijzonderheden()
    {
        return $this->bijzonderheden;
    }

    public function setBijzonderheden($bijzonderheden)
    {
        $this->bijzonderheden = $bijzonderheden;

        return $this;
    }

    public function getRedenOverig()
    {
        return $this->redenOverig;
    }

    public function setRedenOverig($redenOverig)
    {
        $this->redenOverig = $redenOverig;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTerugkeergesprekGehad(): bool
    {
        return $this->terugkeergesprekGehad;
    }

    /**
     * @param bool $terugkeergesprekGehad
     * @return Schorsing
     */
    public function setTerugkeergesprekGehad(bool $terugkeergesprekGehad): Schorsing
    {
        $this->terugkeergesprekGehad = $terugkeergesprekGehad;
        return $this;
    }

    public function heeftTerugkeergesprekNodig(): bool
    {
        $duration = date_diff($this->datumTot, $this->datumVan);
        if($duration->days >= 14) return true;

        return false;
    }

    public function isGezien()
    {
        return $this->gezien;
    }

    public function setGezien($gezien)
    {
        $this->gezien = $gezien;

        return $this;
    }

    public function getAangifte()
    {
        return $this->aangifte;
    }

    public function setAangifte($aangifte)
    {
        $this->aangifte = (bool) $aangifte;

        return $this;
    }

    public function getNazorg()
    {
        return $this->nazorg;
    }

    public function setNazorg($nazorg)
    {
        $this->nazorg = (bool) $nazorg;

        return $this;
    }

    public function isAgressie()
    {
        return $this->agressie;
    }

    public function setAgressie($agressie)
    {
        $this->agressie = (bool) $agressie;

        return $this;
    }

    public function getDoelwitAgressie1()
    {
        return $this->doelwitAgressie1;
    }

    public function setDoelwitAgressie1($doelwitAgressie1)
    {
        $this->doelwitAgressie1 = $doelwitAgressie1;

        return $this;
    }

    public function getDoelwitAgressie2()
    {
        return $this->doelwitAgressie2;
    }

    public function setDoelwitAgressie2($doelwitAgressie2)
    {
        $this->doelwitAgressie2 = $doelwitAgressie2;

        return $this;
    }

    public function getDoelwitAgressie3()
    {
        return $this->doelwitAgressie3;
    }

    public function setDoelwitAgressie3($doelwitAgressie3)
    {
        $this->doelwitAgressie3 = $doelwitAgressie3;

        return $this;
    }

    public function getDoelwitAgressie4()
    {
        return $this->doelwitAgressie4;
    }

    public function setDoelwitAgressie4($doelwitAgressie4)
    {
        $this->doelwitAgressie4 = $doelwitAgressie4;

        return $this;
    }

    public function getTypeDoelwitAgressie1()
    {
        return $this->typeDoelwitAgressie1;
    }

    public function setTypeDoelwitAgressie1($typeDoelwitAgressie1)
    {
        $this->typeDoelwitAgressie1 = $typeDoelwitAgressie1;

        return $this;
    }

    public function getTypeDoelwitAgressie2()
    {
        return $this->typeDoelwitAgressie2;
    }

    public function setTypeDoelwitAgressie2($typeDoelwitAgressie2)
    {
        $this->typeDoelwitAgressie2 = $typeDoelwitAgressie2;

        return $this;
    }

    public function getTypeDoelwitAgressie3()
    {
        return $this->typeDoelwitAgressie3;
    }

    public function setTypeDoelwitAgressie3($typeDoelwitAgressie3)
    {
        $this->typeDoelwitAgressie3 = $typeDoelwitAgressie3;

        return $this;
    }

    public function getTypeDoelwitAgressie4()
    {
        return $this->typeDoelwitAgressie4;
    }

    public function setTypeDoelwitAgressie4($typeDoelwitAgressie4)
    {
        $this->typeDoelwitAgressie4 = $typeDoelwitAgressie4;

        return $this;
    }

    public function getLocatiehoofd()
    {
        return $this->locatiehoofd;
    }

    public function setLocatiehoofd($locatiehoofd)
    {
        $this->locatiehoofd = $locatiehoofd;

        return $this;
    }

    public function getLocaties()
    {
        $l = $this->locaties;
        return $this->locaties;
    }

    public function setLocaties($locaties)
    {
        $this->locaties = $locaties;

        return $this;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant($klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getRedenen()
    {
        return $this->redenen;
    }

    public function addRedenen(SchorsingReden $reden)
    {
        $this->redenen[] = $reden;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->redenen as $reden) {
            if (1 === preg_match('/overig/i', $reden)) {
                if (!$this->redenOverig) {
                    $context->buildViolation('Geef de reden op.')
                        ->atPath('redenOverig')
                        ->addViolation();
                }
            }
        }

        if ($this->agressie) {
            if (!$this->opmerking) {
                $context->buildViolation('Geef een reden op.')
                    ->atPath('opmerking')
                    ->addViolation();
            }

            if (!$this->doelwitAgressie1
                && !$this->doelwitAgressie2
                && !$this->doelwitAgressie3
                && !$this->doelwitAgressie4
            ) {
                $context->buildViolation('Geef aan tegen welke medewerker(s) de agressie gericht is.')
                    ->atPath('doelwitAgressie1')
                    ->addViolation();
            }

            foreach (range(1, 4) as $i) {
                if ($this->{'doelwitAgressie'.$i} && !$this->{'typeDoelwitAgressie'.$i}) {
                    $context->buildViolation('Geef aan wat de functie van de medewerker is.')
                        ->atPath('typeDoelwitAgressie'.$i)
                        ->addViolation();
                }
                if (!$this->{'doelwitAgressie'.$i} && $this->{'typeDoelwitAgressie'.$i}) {
                    $context->buildViolation('Geef de naam van de medewerker op.')
                        ->atPath('doelwitAgressie'.$i)
                        ->addViolation();
                }
            }

            if (null === $this->aangifte) {
                $context->buildViolation('Geef aan of er aangifte gedaan is.')
                    ->atPath('aangifte')
                    ->addViolation();
            }

            if (null === $this->nazorg) {
                $context->buildViolation('Geef aan of er nazorg nodig is.')
                    ->atPath('nazorg')
                    ->addViolation();
            }
        }
    }
}
