<?php

namespace ClipBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Entity\Vraagsoort;
use Doctrine\ORM\QueryBuilder;

class VraagFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $startdatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $openstaand;

    /**
     * @var Vraagsoort
     */
    public $soort;

    /**
     * @var ?Behandelaar
     */
    public $behandelaar;

    /**
     * @var ClientFilter
     */
    public $client;

    /**
     * @var bool
     *
     */
    public $hulpCollegaGezocht;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('vraag.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('vraag.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('vraag.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('vraag.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('vraag.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->openstaand) {
            $builder
                ->andWhere('vraag.afsluitdatum IS NULL OR vraag.afsluitdatum > :today')
                ->setParameter('today', new \DateTime('today'))
            ;
        }

        if ($this->soort) {
            $builder
                ->andWhere('vraag.soort = :vraagsoort')
                ->setParameter('vraagsoort', $this->soort)
            ;
        }

        if ($this->behandelaar) {
            $builder
                ->andWhere('vraag.behandelaar = :behandelaar')
                ->setParameter('behandelaar', $this->behandelaar)
            ;
        }

        if ($this->client) {
            $this->client->applyTo($builder);
        }

        if($this->hulpCollegaGezocht)
        {
            $builder
                ->andWhere('vraag.hulpCollegaGezocht = :hulpGezocht')
                ->setParameter('hulpGezocht',$this->hulpCollegaGezocht);

        }
    }
}
