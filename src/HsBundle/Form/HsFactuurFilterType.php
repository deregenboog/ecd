<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use AppBundle\Form\FilterType;
use HsBundle\Filter\HsFactuurFilter;
use AppBundle\Form\AppDateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HsFactuurFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('nummer', $options['enabled_filters'])) {
            $builder->add('nummer', null, [
                'attr' => ['placeholder' => 'Factuurnummer'],
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

        if (in_array('openstaand', $options['enabled_filters'])) {
            $builder->add('openstaand', CheckboxType::class, [
                'required' => false,
                'label' => 'Openstaand',
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, ['enabled_filters' => $options['enabled_filters']['klant']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsFactuurFilter::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \AppBundle\Form\BaseType::class;
    }
}
