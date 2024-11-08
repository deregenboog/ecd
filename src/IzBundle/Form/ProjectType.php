<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use IzBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('startdatum', AppDateType::class)
            ->add('einddatum', AppDateType::class, [
                'required' => false,
            ])
            ->add('heeftKoppelingen')
            ->add('prestatieStrategy', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Gestart' => Project::STRATEGY_PRESTATIE_STARTED,
                    'Totaal' => Project::STRATEGY_PRESTATIE_TOTAL,
                ],
            ])
            ->add('kleur',TextType::class,[
                'required'=>false,
                'help'=>'Moet een hex code zijn zoals #EEAA00. Hij zoekt er zelf een contrasterende voorgrondkleur bij.'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Project::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
