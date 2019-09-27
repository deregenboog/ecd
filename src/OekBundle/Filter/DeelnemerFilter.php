<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Entity\Deelnemer;
use OekBundle\Entity\Groep;
use OekBundle\Entity\Training;

class DeelnemerFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Groep
     */
    public $groep;

    /**
     * @var Training
     */
    public $training;

    /**
     * @var bool
     */
    public $heeftAfgerondeTraining;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $actief = true;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klant.id = :klant_id')
                ->setParameter('klant_id', $this->id)
            ;
        }

        if ($this->groep) {
            $builder
                ->andWhere('groep = :groep')
                ->setParameter('groep', $this->groep)
            ;
        }

        if ($this->training) {
            $builder
                ->andWhere('training = :training')
                ->setParameter('training', $this->training)
            ;
        }

        if (null !== $this->heeftAfgerondeTraining) {
            $deelnemers = $builder->getEntityManager()->createQueryBuilder()
                ->select('deelnemer.id')
                ->distinct(true)
                ->from(Deelnemer::class, 'deelnemer')
                ->innerJoin('deelnemer.deelnames', 'deelname')
                ->innerJoin('deelname.deelnameStatus', 'deelnameStatus')
                ->where('deelnameStatus.status = :afgerond')
                ->setParameter('afgerond', DeelnameStatus::STATUS_AFGEROND)
                ->getQuery()
                ->getResult()
            ;
            $deelnemerIds = array_map(function ($deelnemer) {
                return $deelnemer['id'];
            }, $deelnemers);

            if ($this->heeftAfgerondeTraining) {
                $builder->andWhere($builder->expr()->in('deelnemer.id', $deelnemerIds));
            } else {
                $builder->andWhere($builder->expr()->notIn('deelnemer.id', $deelnemerIds));
            }
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('aanmelding.datum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('aanmelding.datum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('afsluiting.datum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('afsluiting.datum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->actief) {
            $builder
                ->andWhere('aanmelding.datum <= :today')
                ->andWhere('afsluiting.datum > :today OR afsluiting.datum IS NULL')
                ->setParameter('today', new \DateTime('today'))
            ;

        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
