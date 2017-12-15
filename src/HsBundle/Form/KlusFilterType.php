<?php

namespace HsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\FilterType;
use HsBundle\Filter\KlusFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use HsBundle\Entity\Activiteit;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use HsBundle\Entity\Klus;
use HsBundle\Filter\KlantFilter;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class KlusFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'attr' => ['placeholder' => 'Klusnummer'],
            ]);
        }

        if (in_array('startdatum', $options['enabled_filters'])) {
            $builder->add('startdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('einddatum', $options['enabled_filters'])) {
            $builder->add('einddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('zonderEinddatum', $options['enabled_filters'])) {
            $builder->add('zonderEinddatum', CheckboxType::class, [
                'required' => false,
            ]);
        }

        if (in_array('status', $options['enabled_filters'])) {
            $builder->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    Klus::STATUS_OPENSTAAND => Klus::STATUS_OPENSTAAND,
                    Klus::STATUS_IN_BEHANDELING => Klus::STATUS_IN_BEHANDELING,
                    Klus::STATUS_ON_HOLD => Klus::STATUS_ON_HOLD,
                    Klus::STATUS_AFGEROND => Klus::STATUS_AFGEROND,
                ],
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder
                ->add('klant', KlantFilterType::class, [
                    'enabled_filters' => $options['enabled_filters']['klant'],
                ])
                ->get('klant')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                    $data = $event->getData();
                    $data->status = null;
                    $event->setData($data);
                })
            ;
        }

        if (in_array('activiteit', $options['enabled_filters'])) {
            $builder->add('activiteit', EntityType::class, [
                'required' => false,
                'class' => Activiteit::class,
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
            'data_class' => KlusFilter::class,
            'data' => new KlusFilter(),
            'enabled_filters' => [
                'status',
                'startdatum',
                'einddatum',
                'activiteit',
                'klant' => ['naam', 'stadsdeel'],
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
