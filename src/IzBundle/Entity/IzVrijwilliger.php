<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Vrijwilliger;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzVrijwilligerRepository")
 * @Gedmo\Loggable
 */
class IzVrijwilliger extends IzDeelnemer
{
    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(name="foreign_key", nullable=true)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var ArrayCollection|Hulpaanbod[]
     * @ORM\OneToMany(targetEntity="Hulpaanbod", mappedBy="izVrijwilliger", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "koppelingStartdatum" = "DESC"})
     */
    private $hulpaanbiedingen;

    /**
     * @var ArrayCollection|Lidmaatschap[]
     * @ORM\OneToMany(targetEntity="Lidmaatschap", mappedBy="vrijwilliger", cascade={"persist"})
     */
    private $lidmaatschappen;

    /**
     * @var BinnengekomenVia
     *
     * @ORM\ManyToOne(targetEntity="BinnengekomenVia")
     * @ORM\JoinColumn(name="binnengekomen_via")
     * @Gedmo\Versioned
     */
    protected $binnengekomenVia;

    public function __construct(Vrijwilliger $vrijwilliger = null)
    {
        $this->vrijwilliger = $vrijwilliger;
        $this->datumAanmelding = new \DateTime('today');
        $this->hulpaanbiedingen = new ArrayCollection();
        $this->intervisiegroepen = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->vrijwilliger;
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

    public function getOpenHulpaanbiedingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('hulpvraag'))
            ->andWhere(Criteria::expr()->isNull('einddatum'))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->hulpaanbiedingen->matching($criteria);
    }

    public function getActieveKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->neq('hulpvraag', null))
            ->andWhere(Criteria::expr()->orX(
                Criteria::expr()->isNull('koppelingEinddatum'),
                Criteria::expr()->gte('koppelingEinddatum', new \DateTime('today'))
            ))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->hulpaanbiedingen->matching($criteria);
    }

    public function getAfgeslotenHulpaanbiedingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('hulpvraag'))
            ->andWhere(Criteria::expr()->neq('einddatum', null))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->hulpaanbiedingen->matching($criteria);
    }

    public function getAfgeslotenKoppelingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->neq('hulpvraag', null))
            ->andWhere(Criteria::expr()->lt('koppelingEinddatum', new \DateTime('today')))
            ->orderBy([
                'startdatum' => 'DESC',
                'koppelingStartdatum' => 'DESC',
            ])
        ;

        return $this->hulpaanbiedingen->matching($criteria);
    }

    public function getBinnengekomenVia()
    {
        return $this->binnengekomenVia;
    }

    public function setBinnengekomenVia(BinnengekomenVia $binnengekomenVia = null)
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

            if ($naamA === $naamB) {
                return 0;
            }

            return $naamA > $naamB ? 1 : -1;
        });

        return $intervisiegroepen;
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
}
