<?php

namespace HsBundle\Form;

use AppBundle\Form\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\VrijwilligerFilterType;
use HsBundle\Filter\HsVrijwilligerFilter;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HsVrijwilligerFilterType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'attr' => ['placeholder' => 'Vrijwilligernummer'],
            ]);
        }

        if (in_array('dragend', $options['enabled_filters'])) {
            $builder->add('dragend', CheckboxType::class, [
                'label' => 'Dragend',
                'required' => false,
            ]);
        }

        if (in_array('rijbewijs', $options['enabled_filters'])) {
            $builder->add('rijbewijs', CheckboxType::class, [
                'label' => 'Rijbewijs',
                'required' => false,
            ]);
        }

        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', VrijwilligerFilterType::class, ['enabled_filters' => $options['enabled_filters']['vrijwilliger']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HsVrijwilligerFilter::class,
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
