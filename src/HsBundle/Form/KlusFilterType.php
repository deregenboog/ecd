<?php

namespace HsBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use Doctrine\ORM\EntityRepository;
use HsBundle\Entity\Activiteit;
use HsBundle\Entity\Klus;
use HsBundle\Filter\KlusFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlusFilterType extends AbstractType
{
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

        if (in_array('annuleringsdatum', $options['enabled_filters'])) {
            $builder->add('annuleringsdatum', AppDateRangeType::class, [
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
                    Klus::STATUS_GEANNULEERD => Klus::STATUS_GEANNULEERD,
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
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('activiteit')
                        ->orderBy('activiteit.naam')
                    ;
                },
            ]);
        }

        if (in_array('filter', $options['enabled_filters'])) {
            $builder->add('filter', SubmitType::class);
        }

        if (in_array('download', $options['enabled_filters'])) {
            $builder->add('download', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KlusFilter::class,
            'data' => new KlusFilter(),
            'enabled_filters' => [
                'status',
                'startdatum',
                'einddatum',
                'annuleringsdatum',
                'activiteit',
                'klant' => ['naam', 'stadsdeel'],
                'filter',
                'download',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
