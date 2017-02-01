<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateType;
use OekBundle\Entity\OekGroep;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use OekBundle\Filter\OekKlantFilter;
use AppBundle\Form\FilterType;

class OekKlantFilterType extends AbstractType
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

        if (in_array('groep', $options['enabled_filters'])) {
            $builder->add('groep', EntityType::class, [
                'required' => false,
                'class' => OekGroep::class
            ]);
        }

        if (in_array('aanmelding', $options['enabled_filters'])) {
            $builder->add('aanmelding', AppDateType::class, [
                'required' => false,
            ]);
        }

        if (in_array('afsluiting', $options['enabled_filters'])) {
            $builder->add('afsluiting', AppDateType::class, [
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
            'data_class' => OekKlantFilter::class,
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
