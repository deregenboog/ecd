<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\IzHulpvraag;
use IzBundle\Filter\IzKoppelingFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use IzBundle\Entity\IzHulpaanbod;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\Stadsdeel;
use AppBundle\Form\StadsdeelFilterType;

class IzKoppelingFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('koppelingStartdatum', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('koppelingEinddatum', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => ['placeholder' => 'dd-mm-jjjj'],
            ])
            ->add('lopendeKoppelingen', CheckboxType::class, [
                'required' => false,
                'label' => 'Alleen lopende koppelingen',
            ])
            ->add('klantNaam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam klant'],
            ])
            ->add('vrijwilligerNaam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam vrijwilliger'],
            ])
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
            ->add('izHulpvraagMedewerker', EntityType::class, [
                'required' => false,
                'class' => Medewerker::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('medewerker')
                        ->select('DISTINCT medewerker')
                        ->innerJoin(IzHulpvraag::class, 'izHulpvraag', 'WITH', 'izHulpvraag.medewerker = medewerker')
                        ->orderBy('medewerker.achternaam', 'ASC')
                    ;
                },
            ])
            ->add('izHulpaanbodMedewerker', EntityType::class, [
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
            ->add('stadsdeel', StadsdeelFilterType::class)
            ->add('submit', SubmitType::class, ['label' => 'Filteren'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IzKoppelingFilter::class,
            'method' => 'GET',
        ]);
    }
}
