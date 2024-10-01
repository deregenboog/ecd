<?php

namespace VillaBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use AppBundle\Form\KlantFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VillaBundle\Entity\Aanmelding;
use VillaBundle\Entity\Afsluiting;
use VillaBundle\Entity\Slaper;
use VillaBundle\Filter\SlaperFilter;

class SlaperFilterType extends AbstractType
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

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, ['enabled_filters' => $options['enabled_filters']['klant']]);
        }

        if (in_array('aanmelddatum', $options['enabled_filters'])) {
            $builder->add('aanmelddatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluitdatum', $options['enabled_filters'])) {
            $builder->add('afsluitdatum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }
        if (in_array('type', $options['enabled_filters'])) {
            $builder->add('type', ChoiceType::class, [
                'required' => false,
                'choices'=>
                    [
                        Slaper::$types[Slaper::TYPE_RESPIJT]=>Slaper::TYPE_RESPIJT,
                        Slaper::$types[Slaper::TYPE_LOGEER]=>Slaper::TYPE_LOGEER,
                    ],
            ]);
        }

        if (in_array('dossierStatus', $options['enabled_filters'])) {
            $builder->add('dossierStatus', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Alle dossiers' => null,
                    'Aangemeld' => Aanmelding::class,
                    'Afgesloten' => Afsluiting::class,
                ],
            ]);
        }

        $builder->add('filter', SubmitType::class, [
            'label' => 'Filteren',
        ]);

        $builder->add('download', SubmitType::class, [
            'label' => 'Downloaden',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SlaperFilter::class,
            'data' => new SlaperFilter(),
            'enabled_filters' => [
                'klant' => ['id', 'naam','stadsdeel'],
//                'aanmelddatum',
//                'afsluitdatum',
                'dossierStatus',
                'type',
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
