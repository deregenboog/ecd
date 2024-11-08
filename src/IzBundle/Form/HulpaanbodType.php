<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulp;
use IzBundle\Entity\Hulpaanbod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class HulpaanbodType extends AbstractType
{
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
            ->add('hulpvraagsoorten', HulpvraagsoortSelectType::class, [
                'multiple' => true,
                'constraints' => [new Assert\Count([
                    'min' => 1,
                    'minMessage' => 'Selecteer tenminste één hulpvraagsoort',
                ])],
            ])
            ->add('doelgroepen', null, [
                'required' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('doelgroep')
                        ->where('doelgroep.actief = true')
                        ->orderBy('doelgroep.naam')
                    ;
                },
                'constraints' => [new Assert\Count([
                    'min' => 1,
                    'minMessage' => 'Selecteer tenminste één doelgroep',
                ])],
            ])
            ->add('dagdeel', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Geen voorkeur',
                'choices' => [
                    Hulp::DAGDEEL_OVERDAG => Hulp::DAGDEEL_OVERDAG,
                    Hulp::DAGDEEL_AVOND => Hulp::DAGDEEL_AVOND,
                    Hulp::DAGDEEL_WEEKEND => Hulp::DAGDEEL_WEEKEND,
                    Hulp::DAGDEEL_AVOND_WEEKEND => Hulp::DAGDEEL_AVOND_WEEKEND,
                ],
            ])
            ->add('expat', null, [
                'required' => false,
            ])
            ->add('stagiair', null, [
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpaanbod::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
