<?php

namespace IzBundle\Form;

use AppBundle\Form\BaseType;
use IzBundle\Entity\Hulpvraagsoort;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HulpvraagsoortType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('toelichting')
            ->add('actief')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Hulpvraagsoort::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
