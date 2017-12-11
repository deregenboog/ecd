<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use HsBundle\Filter\KlantFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\StadsdeelFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Werkgebied;

class KlantFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'attr' => ['placeholder' => 'Klantnummer'],
            ]);
        }

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam'],
            ]);
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', EntityType::class, [
                'required' => false,
                'class' => Werkgebied::class,
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    KlantFilter::STATUS_ACTIVE => KlantFilter::STATUS_ACTIVE,
                    KlantFilter::STATUS_NON_ACTIVE => KlantFilter::STATUS_NON_ACTIVE,
                ],
            ]);
        }

        if (in_array('negatiefSaldo', $options['enabled_filters'])) {
            $builder->add('negatiefSaldo', CheckboxType::class, [
                'required' => false,
                'label' => 'Met negatief saldo',
            ]);
        }

        $builder
            ->add('filter', SubmitType::class)
            ->add('download', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlantFilter::class,
            'enabled_filters' => [
                'id',
                'naam',
                'stadsdeel',
                'status',
                'negatiefSaldo',
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }
}
