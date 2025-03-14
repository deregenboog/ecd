<?php

namespace UhkBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UhkBundle\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('actief')
            ->add('color', ColorType::class, [
                'label' => 'Kleur',
                'required' => false,
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
