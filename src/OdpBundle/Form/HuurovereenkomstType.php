<?php

namespace OdpBundle\Form;

use AppBundle\Form\AppDateType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Orx;
use OdpBundle\Entity\Huuraanbod;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Entity\Huurverzoek;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;

class HuurovereenkomstType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof Huurovereenkomst) {
            if (!$options['data']->getId()) {
                if ($options['data']->getHuuraanbod()) {
                    $this->addHuurverzoek($builder, $options['data']);
                } elseif ($options['data']->getHuurverzoek()) {
                    $this->addHuuraanbod($builder, $options['data']);
                }
            }
        } else {
            $this->addHuuraanbod($builder);
            $this->addHuurverzoek($builder);
        }

        $builder
            ->add('medewerker', MedewerkerType::class)
            ->add('startdatum', AppDateType::class, ['data' => new \DateTime()])
            ->add('opzegdatum', AppDateType::class, ['required' => false])
            ->add('einddatum', AppDateType::class, ['required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Huurovereenkomst::class,
        ]);
    }

    private function addHuurverzoek(FormBuilderInterface $builder, Huurovereenkomst $huurovereenkomst = null)
    {
        $builder->add('huurverzoek', EntityType::class, [
            'class' => Huurverzoek::class,
            'placeholder' => 'Selecteer een huurverzoek',
            'query_builder' => function (EntityRepository $repository) use ($huurovereenkomst) {
                $builder = $repository->createQueryBuilder('huurverzoek')
                    ->leftJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst')
                    ->where('huurovereenkomst.id IS NULL')
                ;

                if ($huurovereenkomst instanceof Huurovereenkomst
                    && $huurovereenkomst->getHuuraanbod() instanceof Huuraanbod
                ) {
                    $builder
                        ->andWhere(new Orx([
                            'huurverzoek.startdatum BETWEEN :start AND :eind',
                            'huurverzoek.startdatum >= :start AND :eind IS NULL',
                            'huurverzoek.afsluitdatum BETWEEN :start AND :eind',
                            'huurverzoek.afsluitdatum >= :start AND :eind IS NULL',
                            'huurverzoek.afsluitdatum IS NULL',
                        ]))
                        ->setParameter('start', $huurovereenkomst->getHuuraanbod()->getStartdatum())
                        ->setParameter('eind', $huurovereenkomst->getHuuraanbod()->getAfsluitdatum())
                    ;
                }

                return $builder;
            },
        ]);
    }

    private function addHuuraanbod(FormBuilderInterface $builder, Huurovereenkomst $huurovereenkomst = null)
    {
        $builder->add('huuraanbod', EntityType::class, [
            'class' => Huuraanbod::class,
            'placeholder' => 'Selecteer een huuraanbod',
            'query_builder' => function (EntityRepository $repository) use ($huurovereenkomst) {
                $builder = $repository->createQueryBuilder('huuraanbod')
                    ->leftJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
                    ->where('huurovereenkomst.id IS NULL')
                ;

                if ($huurovereenkomst instanceof Huurovereenkomst
                    && $huurovereenkomst->getHuurverzoek() instanceof Huurverzoek
                ) {
                    $builder
                        ->andWhere(new Orx([
                            'huuraanbod.startdatum BETWEEN :start AND :eind',
                            'huuraanbod.startdatum >= :start AND :eind IS NULL',
                            'huuraanbod.afsluitdatum BETWEEN :start AND :eind',
                            'huuraanbod.afsluitdatum >= :start AND :eind IS NULL',
                            'huuraanbod.afsluitdatum IS NULL',
                        ]))
                        ->setParameter('start', $huurovereenkomst->getHuurverzoek()->getStartdatum())
                        ->setParameter('eind', $huurovereenkomst->getHuurverzoek()->getAfsluitdatum())
                    ;
                }

                return $builder;
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
