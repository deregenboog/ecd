<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Form\Model\AppIntRangeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppIntRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('low', IntegerType::class, [
                'label' => 'Van',
                'attr' => ['placeholder' => 'Van'],
            ])
            ->add('high', IntegerType::class, [
                'label' => 'Tot',
                'attr' => ['placeholder' => 'Tot'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppIntRangeModel::class,
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
