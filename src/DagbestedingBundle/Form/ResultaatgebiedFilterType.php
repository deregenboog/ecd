<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\FilterType;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Filter\ResultaatgebiedFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultaatgebiedFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('soort', $options['enabled_filters'])) {
            $builder->add('soort', EntityType::class, [
                'class' => Resultaatgebiedsoort::class,
                'required' => false,
            ]);
        }
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResultaatgebiedFilter::class,
            'enabled_filters' => [
                'soort',
            ],
        ]);
    }
}
