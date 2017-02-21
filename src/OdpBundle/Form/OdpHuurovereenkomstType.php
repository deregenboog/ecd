<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;
use OdpBundle\Entity\OdpHuuraanbod;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Entity\OdpHuurverzoek;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OdpHuurovereenkomstType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof OdpHuurovereenkomst) {
            if ($options['data']->getOdpHuuraanbod()) {
                $this->setOdpHuurverzoek($builder, $options);
            } elseif ($options['data']->getOdpHuurverzoek()) {
                $this->setOdpHuuraanbod($builder, $options);
            }
        } else {
            $this->setOdpHuuraanbod($builder, $options);
            $this->setOdpHuurverzoek($builder, $options);
        }

        $builder->add('startdatum', AppDateType::class);
        $builder->add('einddatum', AppDateType::class, ['required' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OdpHuurovereenkomst::class,
            'enabled_filters' => [],
        ]);
    }

    private function setOdpHuurverzoek(FormBuilderInterface $builder, array $options)
    {
        $builder->add('odpHuurverzoek', EntityType::class, [
            'class' => OdpHuurverzoek::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                $odpHuurovereenkomst = $options['data'];

                $builder = $repository->createQueryBuilder('huurverzoek')
                    ->leftJoin('huurverzoek.odpHuurovereenkomst', 'huurovereenkomst');

                if (
                    $odpHuurovereenkomst instanceof OdpHuurovereenkomst &&
                    $odpHuuraanbod = $odpHuurovereenkomst->getOdpHuuraanbod()
                ) {
                    $builder->where(new Orx([
                                'huurverzoek.startdatum BETWEEN :start AND :eind',
                                'huurverzoek.startdatum >= :start AND :eind IS NULL',
                                'huurverzoek.einddatum BETWEEN :start AND :eind',
                                'huurverzoek.einddatum >= :start AND :eind IS NULL',
                                'huurverzoek.einddatum IS NULL',
                            ]))
                            ->andWhere('huurovereenkomst.id IS NULL')
                            ->setParameter('start', $odpHuuraanbod->getStartdatum())
                            ->setParameter('eind', $odpHuuraanbod->getEinddatum());
                }

                return $builder;
            },
        ]);
    }

    private function setOdpHuuraanbod(FormBuilderInterface $builder, array $options)
    {
        $builder->add('odpHuuraanbod', EntityType::class, [
            'class' => OdpHuuraanbod::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                $odpHuurovereenkomst = $options['data'];

                $builder = $repository->createQueryBuilder('huuraanbod')
                    ->leftJoin('huuraanbod.odpHuurovereenkomst', 'huurovereenkomst');

                if (
                    $odpHuurovereenkomst instanceof OdpHuurovereenkomst &&
                    $odpHuurverzoek = $odpHuurovereenkomst->getOdpHuurverzoek()
                ) {
                    $builder->where(new Orx([
                                'huuraanbod.startdatum BETWEEN :start AND :eind',
                                'huuraanbod.startdatum >= :start AND :eind IS NULL',
                                'huuraanbod.einddatum BETWEEN :start AND :eind',
                                'huuraanbod.einddatum >= :start AND :eind IS NULL',
                                'huuraanbod.einddatum IS NULL',
                            ]))
                            ->andWhere('huurovereenkomst.id IS NULL')
                            ->setParameter('start', $odpHuurverzoek->getStartdatum())
                            ->setParameter('eind', $odpHuurverzoek->getEinddatum());
                }

                return $builder;
            },
        ]);
    }
}
