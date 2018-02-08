<?php

namespace InloopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use InloopBundle\Entity\Locatie;
use InloopBundle\Filter\SchorsingFilter;

class SchorsingFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', AppKlantFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['klant'],
            ]);
        }

        if (in_array('locatie', $options['enabled_filters'])) {
            $builder->add('locatie', LocatieSelectType::class, [
                'required' => false,
            ]);
        }

        if (in_array('datumVan', $options['enabled_filters'])) {
            $builder->add('datumVan', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        if (in_array('datumTot', $options['enabled_filters'])) {
            $builder->add('datumTot', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
//             ->add('download', SubmitType::class, ['label' => 'Downloaden'])
        ;
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SchorsingFilter::class,
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'geslacht'],
                'locatie',
                'datumVan',
                'datumTot',
            ],
        ]);
    }
}
