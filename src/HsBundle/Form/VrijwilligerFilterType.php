<?php

namespace HsBundle\Form;

use AppBundle\Form\FilterType;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use HsBundle\Filter\ArbeiderFilter;
use HsBundle\Filter\VrijwilligerFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

        if (in_array('hulpverlener', $options['enabled_filters'])) {
            $builder->add('hulpverlener', null, [
                'required' => false,
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

        if (key_exists('vrijwilliger', $options['enabled_filters'])) {
            $builder->add('vrijwilliger', AppVrijwilligerFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vrijwilliger'],
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
            'data_class' => VrijwilligerFilter::class,
            'data' => new VrijwilligerFilter(),
            'enabled_filters' => [
                'hulpverlener',
                'rijbewijs',
                'status',
                'vrijwilliger' => ['id', 'naam', 'adres', 'geboortedatumRange', 'stadsdeel'],
                'filter',
                'download',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
