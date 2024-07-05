<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\AppIntRangeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppIntRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('low', AppIntType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Van', 'class' => 'small'],
            ])
            ->add('high', AppIntType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Tot', 'class' => 'small'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppIntRangeModel::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
