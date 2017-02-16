<?php

namespace OekBundle\Form;

use AppBundle\Form\AppTimeType;
use OekBundle\Entity\OekTraining;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\AppDateType;

class OekTrainingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('naam');
        $builder->add('locatie');
        $builder->add('oekGroep', null, [
            'label' => 'Groep',
            'placeholder' => 'Selecteer een item',
        ]);
        $builder->add('startdatum', AppDateType::class);
        $builder->add('starttijd', AppTimeType::class);
        $builder->add('einddatum', AppDateType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OekTraining::class,
        ]);
    }
}
