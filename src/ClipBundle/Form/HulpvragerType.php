<?php

namespace ClipBundle\Form;

use AppBundle\Form\BaseType;
use ClipBundle\Entity\Hulpvrager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HulpvragerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('actief')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpvrager::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
