<?php

namespace IzBundle\Form;

use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Werkgebied;
use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerSelectType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulp;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HulpaanbodType extends AbstractType
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
            ->add('medewerker', MedewerkerSelectType::class, [
                'required' => true,
            ])
            ->add('hulpvraagsoorten', HulpvraagsoortSelectType::class, [
                'multiple' => true,
            ])
            ->add('voorkeurStadsdelen', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'class' => Werkgebied::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('stadsdeel')
                        ->orderBy('stadsdeel.naam')
                    ;
                },
            ])
            ->add('beschikbareDagen', ChoiceType::class, [
                'required' => false,
                'choices' => array_combine(array_keys(Hulp::DAGEN), array_keys(Hulp::DAGEN)),
                'expanded' => true,
                'multiple' => true,
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
            ->add('voorkeurMinLeeftijd', NumberType::class, [
                'required' => false,
                'label' => 'Voorkeur leeftijd minimaal',
            ])
            ->add('voorkeurMaxLeeftijd', NumberType::class, [
                'required' => false,
                'label' => 'Voorkeur leeftijd maximaal',
            ])
            ->add('info', AppTextareaType::class, [
                'label' => 'Info m.b.t. matching',
                'attr' => [
                    'cols' => 50,
                    'rows' => 4,
                ],
            ])
            ->add('voorkeurNietRoker', null, [
                'label' => 'Voorkeur voor niet-roker',
                'required' => false,
            ])
            ->add('voorkeurGeenDieren', null, [
                'label' => 'Voorkeur voor geen huisdieren',
                'required' => false,
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
            'data_class' => Hulpaanbod::class,
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
