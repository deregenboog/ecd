<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\MedewerkerType;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Koppeling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('medewerker', MedewerkerType::class, [
                'required' => true,
            ])
            ->add('hulpvraagsoorten', HulpvraagsoortSelectType::class, [
                'multiple' => true,
            ])
            ->add('doelgroepen', DoelgroepSelectType::class, [
                'multiple' => true,
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
            ->add('expat', null, [
                'required' => false,
            ])
            ->add('coachend', null, [
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
