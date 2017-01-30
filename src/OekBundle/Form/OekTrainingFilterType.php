<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateType;
use OekBundle\Entity\OekGroep;
use OekBundle\Filter\OekTrainingFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use OekBundle\Filter\OekKlantFilter;
use AppBundle\Form\FilterType;

class OekTrainingFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'attr' => ['placeholder' => 'Trainingnummer'],
            ]);
        }

        if (key_exists('klant', $options['enabled_filters'])) {
            $builder->add('klant', KlantFilterType::class, ['enabled_filters' => $options['enabled_filters']['klant']]);
        }

        if (in_array('training_oekGroep', $options['enabled_filters'])) {
            $builder->add('training_oekGroep', EntityType::class, [
                'required' => false,
                'class' => OekGroep::class
            ]);
        }

        if (in_array('startDatum', $options['enabled_filters'])) {
            $builder->add('startDatum', null, [
                'attr' => ['placeholder' => 'Start'],
                'required' => false,
            ]);
        }

        if (in_array('eindDatum', $options['enabled_filters'])) {
            $builder->add('eindDatum', null, [
                'attr' => ['placeholder' => 'Eind'],
                'required' => false,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekTrainingFilter::class,
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
