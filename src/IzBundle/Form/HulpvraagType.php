<?php

namespace IzBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use AppBundle\Form\BaseType;
use AppBundle\Form\AppDateType;
use AppBundle\Form\MedewerkerType;
use IzBundle\Entity\Hulpvraag;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use IzBundle\Entity\Koppeling;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\AppTextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use IzBundle\Entity\Doelgroep;

class HulpvraagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdatum', AppDateType::class, [
                'required' => true,
            ])
            ->add('project', ProjectSelectType::class, [
                'required' => true,
            ])
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
            ])
            ->add('hulpvraagsoort', HulpvraagsoortSelectType::class, [
                'required' => true,
            ])
            ->add('doelgroep', EntityType::class, [
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'class' => Doelgroep::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('doelgroep')
                        ->where('doelgroep.actief = true')
                        ->orderBy('doelgroep.naam')
                    ;
                },
            ])
            ->add('dagdeel', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Geen voorkeur',
                'choices' => [
                    Koppeling::DAGDEEL_OVERDAG => Koppeling::DAGDEEL_OVERDAG,
                    Koppeling::DAGDEEL_AVOND => Koppeling::DAGDEEL_AVOND,
                    Koppeling::DAGDEEL_WEEKEND => Koppeling::DAGDEEL_WEEKEND,
                    Koppeling::DAGDEEL_AVOND_WEEKEND => Koppeling::DAGDEEL_AVOND_WEEKEND,
                ],
            ])
            ->add('geschiktVoorExpat', null, [
                'required' => false,
            ])
            ->add('voorkeurGeslacht', null, [
                'required' => false,
                'placeholder' => 'Geen voorkeur',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('geslacht')
                        ->where('geslacht.volledig <> :onbekend')
                        ->setParameter('onbekend', 'Onbekend')
                    ;
                },
            ])
            ->add('info', AppTextareaType::class, [
                'label' => 'Info m.b.t. matching',
                'attr' => [
                    'cols' => 50,
                    'rows' => 4,
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpvraag::class,
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
