<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateRangeType;
use AppBundle\Form\FilterType;
use OekBundle\Entity\Groep;
use OekBundle\Filter\TrainingFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'attr' => ['placeholder' => 'Nummer'],
            ]);
        }

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'attr' => ['placeholder' => 'Naam'],
            ]);
        }

        if (in_array('groep', $options['enabled_filters'])) {
            $builder->add('groep', EntityType::class, [
                'required' => false,
                'class' => Groep::class,
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrainingFilter::class,
            'enabled_filters' => [
                'id',
                'naam',
                'groep',
                'startdatum',
                'einddatum',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
