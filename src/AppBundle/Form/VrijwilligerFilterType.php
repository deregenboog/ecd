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
        $builder
            ->add('id', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Vrijwilligernummer'],
            ])
            ->add('naam', null, [
                'required' => false,
                'attr' => ['placeholder' => 'Naam vrijwilliger'],
            ])
            ->add('geboortedatum', BirthdayType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('stadsdeel', StadsdeelFilterType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VrijwilligerFilter::class,
            'data' => null,
        ]);
    }
}
