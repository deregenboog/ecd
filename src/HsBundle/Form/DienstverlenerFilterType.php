<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use HsBundle\Filter\DienstverlenerFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use HsBundle\Filter\ArbeiderFilter;

class DienstverlenerFilterType extends AbstractType
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

        if (in_array('rijbewijs', $options['enabled_filters'])) {
            $builder->add('rijbewijs', CheckboxType::class, [
                'label' => 'Rijbewijs',
                'required' => false,
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    ArbeiderFilter::STATUS_ACTIVE => ArbeiderFilter::STATUS_ACTIVE,
                    ArbeiderFilter::STATUS_NON_ACTIVE => ArbeiderFilter::STATUS_NON_ACTIVE,
                ],
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DienstverlenerFilter::class,
            'data' => new DienstverlenerFilter(),
            'enabled_filters' => [
                'rijbewijs',
                'status',
                'klant' => ['id', 'naam', 'geboortedatumRange', 'stadsdeel'],
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
