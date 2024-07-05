<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\AppTimeRangeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppTimeRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', AppTimeType::class, [
                'label' => 'Van',
                'attr' => ['placeholder' => 'Van (uu:mm)'],
            ])
            ->add('end', AppTimeType::class, [
                'label' => 'Tot',
                'attr' => ['placeholder' => 'Tot (uu:mm)'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppTimeRangeModel::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
