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

class HuurovereenkomstType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof Huurovereenkomst) {
            if ($options['data']->getHuuraanbod()) {
                $this->setHuurverzoek($builder, $options);
            } elseif ($options['data']->getHuurverzoek()) {
                $this->setHuuraanbod($builder, $options);
            }
        } else {
            $this->setHuuraanbod($builder, $options);
            $this->setHuurverzoek($builder, $options);
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

    private function setHuurverzoek(FormBuilderInterface $builder, array $options)
    {
        $builder->add('huurverzoek', EntityType::class, [
            'class' => Huurverzoek::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                $huurovereenkomst = $options['data'];

                $builder = $repository->createQueryBuilder('huurverzoek')
                    ->leftJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst');

                if (
                    $huurovereenkomst instanceof Huurovereenkomst &&
                    $huuraanbod = $huurovereenkomst->getHuuraanbod()
                ) {
                    $builder->where(new Orx([
                            'huurverzoek.startdatum BETWEEN :start AND :eind',
                            'huurverzoek.startdatum >= :start AND :eind IS NULL',
                            'huurverzoek.einddatum BETWEEN :start AND :eind',
                            'huurverzoek.einddatum >= :start AND :eind IS NULL',
                            'huurverzoek.einddatum IS NULL',
                        ]))
                        ->andWhere('huurovereenkomst.id IS NULL')
                        ->setParameter('start', $huuraanbod->getStartdatum())
                        ->setParameter('eind', $huuraanbod->getEinddatum())
                    ;
                }

                return $builder;
            },
        ]);
    }

    private function setHuuraanbod(FormBuilderInterface $builder, array $options)
    {
        $builder->add('huuraanbod', EntityType::class, [
            'class' => Huuraanbod::class,
            'query_builder' => function (EntityRepository $repository) use ($options) {
                $huurovereenkomst = $options['data'];

                $builder = $repository->createQueryBuilder('huuraanbod')
                    ->leftJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst');

                if (
                    $huurovereenkomst instanceof Huurovereenkomst &&
                    $huurverzoek = $huurovereenkomst->getHuurverzoek()
                ) {
                    $builder->where(new Orx([
                            'huuraanbod.startdatum BETWEEN :start AND :eind',
                            'huuraanbod.startdatum >= :start AND :eind IS NULL',
                            'huuraanbod.einddatum BETWEEN :start AND :eind',
                            'huuraanbod.einddatum >= :start AND :eind IS NULL',
                            'huuraanbod.einddatum IS NULL',
                        ]))
                        ->andWhere('huurovereenkomst.id IS NULL')
                        ->setParameter('start', $huurverzoek->getStartdatum())
                        ->setParameter('eind', $huurverzoek->getEinddatum())
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
        return \AppBundle\Form\BaseType::class;
    }
}
