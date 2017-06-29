<?php

namespace ClipBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\FilterType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\AppDateRangeType;
use ClipBundle\Filter\VraagFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ClipBundle\Entity\Vraagsoort;
use AppBundle\Entity\Medewerker;
use ClipBundle\Filter\ContactmomentFilter;

class ContactmomentFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, []);
        }

        if (array_key_exists('vraag', $options['enabled_filters'])) {
            $builder->add('vraag', VraagFilterType::class, [
                'enabled_filters' => $options['enabled_filters']['vraag'],
            ]);
        }

        if (in_array('medewerker', $options['enabled_filters'])) {
            $builder->add('medewerker', MedewerkerType::class, [
                'required' => false,
            ]);
        }

        if (in_array('datum', $options['enabled_filters'])) {
            $builder->add('datum', AppDateRangeType::class, [
                'required' => false,
            ]);
        }

        $builder
            ->add('filter', SubmitType::class, ['label' => 'Filteren'])
            ->add('download', SubmitType::class, ['label' => 'Downloaden'])
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
            'data_class' => ContactmomentFilter::class,
            'enabled_filters' => [
                'id',
                'vraag' => ['soort', 'client' => ['klant' => ['naam']]],
                'medewerker',
                'datum',
            ],
        ]);
    }
}
