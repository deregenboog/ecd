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
                'attr' => ['placeholder' => 'Van (dd-mm-jjjj)'],
            ])
            ->add('end', AppDateType::class, [
                'label' => 'Tot',
                'attr' => ['placeholder' => 'Tot (dd-mm-jjjj)'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppDateRangeModel::class,
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
