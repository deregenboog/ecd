<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use HsBundle\Filter\HsKlantFilter;
use AppBundle\Form\FilterType;
use HsBundle\Filter\HsFactuurFilter;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use HsBundle\Filter\HsBetalingFilter;

class HsBetalingFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('referentie', $options['enabled_filters'])) {
            $builder->add('referentie', null, [
                'attr' => ['placeholder' => 'Referentie'],
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateType::class, ['required' => false]);
        }

        if (in_array('bedrag', $options['enabled_filters'])) {
            $builder->add('bedrag', MoneyType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Bedrag'],
            ]);
        }

        if (key_exists('hsFactuur', $options['enabled_filters'])) {
            $builder->add('hsFactuur', HsFactuurFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['hsFactuur'],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsBetalingFilter::class,
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
