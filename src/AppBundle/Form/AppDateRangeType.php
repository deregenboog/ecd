<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\AppDateRangeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppDateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', AppDateType::class, [
                'label' => 'Van',
                'attr' => ['placeholder' => 'Van (dd-mm-jjjj)', 'class' => 'medium'],
            ])
            ->add('end', AppDateType::class, [
                'label' => 'Tot',
                'attr' => ['placeholder' => 'Tot (dd-mm-jjjj)', 'class' => 'medium'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppDateRangeModel::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
