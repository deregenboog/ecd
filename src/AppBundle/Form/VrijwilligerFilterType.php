<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\VrijwilligerFilter;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class VrijwilligerFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('id', $options['enabled_filters'])) {
            $builder->add('id', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Vrijwilligernummer'],
            ]);
        }

        if (in_array('naam', $options['enabled_filters'])) {
            $builder->add('naam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam vrijwilliger'],
            ]);
        }

        if (in_array('geboortedatum', $options['enabled_filters'])) {
            $builder->add('geboortedatum', BirthdayType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ]);
        }

        if (in_array('stadsdeel', $options['enabled_filters'])) {
            $builder->add('stadsdeel', StadsdeelFilterType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerFilter::class,
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
