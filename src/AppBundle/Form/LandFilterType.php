<?php

namespace AppBundle\Form;

use AppBundle\Filter\LandFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam');
        }

        $builder->add('filter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LandFilter::class,
            'enabled_filters' => [
                'naam',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}
