<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzVrijwilligerRepository")
 *
 * @Gedmo\Loggable
 */
class IzVrijwilliger extends IzDeelnemer
{
    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     *
     * @ORM\JoinColumn(name="foreign_key")
     *
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var ArrayCollection|Hulpaanbod[]
     *
     * @ORM\OneToMany(targetEntity="Hulpaanbod", mappedBy="izVrijwilliger", cascade={"persist"})
     *
     * @ORM\OrderBy({"startdatum" = "DESC", "koppelingStartdatum" = "DESC"})
     */
    private $hulpaanbiedingen;

    /**
     * @var ArrayCollection|Lidmaatschap[]
     *
     * @ORM\OneToMany(targetEntity="Lidmaatschap", mappedBy="vrijwilliger", cascade={"persist"},fetch="EXTRA_LAZY")
     */
    private $lidmaatschappen;

    /**
     * @var BinnengekomenVia
     *
     * @ORM\ManyToOne(targetEntity="BinnengekomenVia",fetch="EXTRA_LAZY")
     *
     * @ORM\JoinColumn(name="binnengekomen_via")
     *
     * @Gedmo\Versioned
     */
    protected $binnengekomenVia;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname",mappedBy="vrijwilliger", cascade={"persist", "remove"})
     */
    protected $trainingDeelnames;


    public function __construct(?Vrijwilliger $vrijwilliger = null)
    {
        $this->vrijwilliger = $vrijwilliger;
        $this->datumAanmelding = new \DateTime('today');
        $this->hulpaanbiedingen = new ArrayCollection();
        $this->lidmaatschappen = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->vrijwilliger);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    public function getHulpaanbiedingen()
    {
        return $this->hulpaanbiedingen;
    }

    public function addHulpaanbod(Hulpaanbod $hulpaanbod)
    {
        $this->hulpaanbiedingen[] = $hulpaanbod;
        $hulpaanbod->setIzVrijwilliger($this);

        return $this;
    }

    public function getNietGekoppeldeHulpaanbiedingen()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->isNull('hulpvraag'));

        return $this->hulpaanbiedingen->matching($criteria);
    }

    public function getGekoppeldeHulpaanbiedingen()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->neq('hulpvraag', null));

        return $this->hulpaanbiedingen->matching($criteria);
    }

    public function getOpenHulpaanbiedingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->orX(
                Criteria::expr()->isNull('einddatum'),
                Criteria::expr()->gt('einddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getNietGekoppeldeHulpaanbiedingen()->matching($criteria);
    }

    public function getActieveKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->orX(
                Criteria::expr()->isNull('koppelingEinddatum'),
                Criteria::expr()->gt('koppelingEinddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getGekoppeldeHulpaanbiedingen()->matching($criteria);
    }

    public function getAfgeslotenHulpaanbiedingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->andX(
                Criteria::expr()->neq('einddatum', null),
                Criteria::expr()->lte('einddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getNietGekoppeldeHulpaanbiedingen()->matching($criteria);
    }

    public function getAfgeslotenKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->andX(
                Criteria::expr()->neq('koppelingEinddatum', null),
                Criteria::expr()->lte('koppelingEinddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->getGekoppeldeHulpaanbiedingen()->matching($criteria);
    }

    public function getBinnengekomenVia()
    {
        return $this->binnengekomenVia;
    }

    public function setBinnengekomenVia(?BinnengekomenVia $binnengekomenVia = null)
    {
        $this->binnengekomenVia = $binnengekomenVia;

        return $this;
    }

    public function getIntervisiegroepen()
    {
        $intervisiegroepen = [];

        foreach ($this->lidmaatschappen as $lidmaatschap) {
            $intervisiegroepen[] = $lidmaatschap->getIntervisiegroep();
        }

        $intervisiegroepen = array_filter($intervisiegroepen);
        usort($intervisiegroepen, function (Intervisiegroep $a, Intervisiegroep $b) {
            $naamA = $a->getNaam();
            $naamB = $b->getNaam();

            return $naamB <=> $naamA;
        });

        return $intervisiegroepen;
    }

    public function getLidmaatschappen()
    {
        return $this->lidmaatschappen;
    }

    public function addLidmaatschap(Lidmaatschap $lidmaatschap)
    {
        $this->lidmaatschappen[] = $lidmaatschap;
        $lidmaatschap->setVrijwilliger($this);

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }

    public function isCloseable()
    {
        return !$this->isAfgesloten()
            && 0 === (is_array($this->getOpenHulpaanbiedingen()) || $this->getOpenHulpaanbiedingen() instanceof \Countable ? count($this->getOpenHulpaanbiedingen()) : 0) + (is_array($this->getActieveKoppelingen()) || $this->getActieveKoppelingen() instanceof \Countable ? count($this->getActieveKoppelingen()) : 0);
    }

    /**
     * @return ArrayCollection|Deelname[]
     */
    public function getTrainingDeelnames()
    {
        return $this->trainingDeelnames;
    }

    /**
     * @param ArrayCollection|Deelname[] $trainingDeelnames
     */
    public function setTrainingDeelnames($trainingDeelnames): void
    {
        $this->trainingDeelnames = $trainingDeelnames;
    }

    public function addDeelname(Deelname $deelname)
    {
        $this->trainingDeelnames[] = $deelname;
    }

    public function removeDeelname(Deelname $deelname)
    {
        $this->trainingDeelnames->removeElement($deelname);
    }
}
