<?php

namespace VillaBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Entity\Slaper;
use OekBundle\Entity\Groep;
use OekBundle\Entity\Training;
use VillaBundle\Entity\Aanmelding;

class SlaperFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;


    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var type zorg.
     */
    public $type;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var string
     */
    public $dossierStatus = Aanmelding::class;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klant.id = :klant_id')
                ->setParameter('klant_id', $this->id)
            ;
        }

        if($this->type)
        {
            if ($this->type) {
                $builder
                    ->andWhere('slaper.type = :slaper_type')
                    ->setParameter('slaper_type', $this->type);
            }
        }

//        if ($this->aanmelddatum) {
//            if ($this->aanmelddatum->getStart()) {
//                $builder
//                    ->andWhere('aanmelding.datum >= :aanmelddatum_van')
//                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
//                ;
//            }
//            if ($this->aanmelddatum->getEnd()) {
//                $builder
//                    ->andWhere('aanmelding.datum <= :aanmelddatum_tot')
//                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
//                ;
//            }
//        }
//
//        if ($this->afsluitdatum) {
//            if ($this->afsluitdatum->getStart()) {
//                $builder
//                    ->andWhere('afsluiting.datum >= :afsluitdatum_van')
//                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
//                ;
//            }
//            if ($this->afsluitdatum->getEnd()) {
//                $builder
//                    ->andWhere('afsluiting.datum <= :afsluitdatum_tot')
//                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
//                ;
//            }
//        }


        if ($this->dossierStatus) {
            $builder->andWhere($builder->expr()->isInstanceOf('ds', $this->dossierStatus));
        }

        if ($this->klant) {
            $this->klant->applyTo($builder,'appKlant');
        }
    }
}
