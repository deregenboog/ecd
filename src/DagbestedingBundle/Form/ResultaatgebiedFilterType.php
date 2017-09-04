<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\FilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Filter\ResultaatgebiedFilter;

class ResultaatgebiedFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('soort', $options['enabled_filters'])) {
            $builder->add('soort', EntityType::class, [
                'class' => Resultaatgebiedsoort::class,
                'required' => false,
            ]);
        }
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
            'data_class' => ResultaatgebiedFilter::class,
            'enabled_filters' => [
                'soort',
            ],
        ]);
    }
}
