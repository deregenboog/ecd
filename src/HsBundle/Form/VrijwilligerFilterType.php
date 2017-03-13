<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use HsBundle\Filter\VrijwilligerFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VrijwilligerFilterType extends AbstractType
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
            $builder->add('vrijwilliger', AppVrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
            ]);
        }

        if (in_array('filter', $options['enabled_filters'])) {
            $builder->add('filter', SubmitType::class);
        }

        if (in_array('download', $options['enabled_filters'])) {
            $builder->add('download', SubmitType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerFilter::class,
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
