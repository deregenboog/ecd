<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use IzBundle\Entity\Doelstelling;
use AppBundle\Form\BaseType;

class DoelstellingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $range = range(2017, (new \DateTime('next year'))->format('Y'));

        $builder
            ->add('jaar', ChoiceType::class, [
                'choices' => array_combine($range, $range),
            ])
            ->add('project', ProjectSelectType::class, ['placeholder' => 'Selecteer een project'])
            ->add('categorie', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Stadsdeel',
                'choices' => [
                    'Centrale stad' => Doelstelling::CATEGORIE_CENTRALE_STAD,
                    'Fondsen' => Doelstelling::CATEGORIE_FONDSEN,
                ],
            ])
            ->add('stadsdeel')
            ->add('aantal')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Doelstelling::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
