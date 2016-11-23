<?php

namespace IzBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\StadsdeelFilterType;
use AppBundle\Form\VrijwilligerFilterType;
use IzBundle\Entity\IzHulpaanbod;
use IzBundle\Entity\IzProject;
use IzBundle\Filter\IzHulpaanbodFilter;
use IzBundle\Entity\IzHulpvraag;

class IzHulpaanbodFilterType extends IzKoppelingFilterType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('vrijwilliger', VrijwilligerFilterType::class)
            ->add('izProject', EntityType::class, [
                'required' => false,
                'class' => IzProject::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('izProject')
                        ->where('izProject.einddatum IS NULL OR izProject.einddatum >= :now')
                        ->orderBy('izProject.naam', 'ASC')
                        ->setParameter('now', new \DateTime())
                        ;
                    },
                ])
            ->add('medewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(IzHulpaanbod::class, 'izHulpaanbod', 'WITH', 'izHulpaanbod.medewerker = medewerker')
                        ->orderBy('medewerker.achternaam', 'ASC')
                    ;
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'Filteren'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzHulpaanbodFilter::class,
            'method' => 'GET',
        ]);
    }
}
