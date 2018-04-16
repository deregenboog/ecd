<?php

namespace IzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use IzBundle\Entity\Project;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Project::class,
        ]);
    }

    public function getParent()
    {
        return BaseType::class;
    }
}
