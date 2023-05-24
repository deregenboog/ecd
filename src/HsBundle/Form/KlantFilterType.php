<?php

namespace HsBundle\Form;

use AppBundle\Entity\Werkgebied;
use AppBundle\Form\FilterType;
use HsBundle\Filter\KlantFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

        if (in_array('hulpverlener', $options['enabled_filters'])) {
            $builder->add('hulpverlener', null, [
                'required' => false,
            ]);
        }

        if (in_array('adres', $options['enabled_filters'])) {
            $builder->add('adres', null, [
                'required' => false,
            ]);
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', EntityType::class, [
                'required' => false,
                'class' => Werkgebied::class,
            ]);
        }

        if (in_array('afwijkendFactuuradres', $options['enabled_filters'])) {
            $builder->add('afwijkendFactuuradres', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Nee' => 0,
                    'Ja' => 1,
                ],
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    KlantFilter::STATUS_ACTIVE => KlantFilter::STATUS_ACTIVE,
                    KlantFilter::STATUS_NON_ACTIVE => KlantFilter::STATUS_NON_ACTIVE,
                    KlantFilter::STATUS_GEEN_NIEUWE_KLUSSEN => KlantFilter::STATUS_GEEN_NIEUWE_KLUSSEN,
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
            'data' => new KlantFilter(),
            'enabled_filters' => [
                'id',
                'naam',
                'hulpverlener',
                'adres',
                'stadsdeel',
                'afwijkendFactuuradres',
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
    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
