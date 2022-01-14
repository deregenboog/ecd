<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTimeType;
use AppBundle\Form\BaseType;
use OekBundle\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['data']) || !$options['data']->getGroep()) {
            $builder->add('groep', null, [
                'label' => 'Groep',
                'placeholder' => 'Selecteer een item',
            ]);
        }

        $builder
            ->add('naam')
            ->add('locatie')
            ->add('startdatum', AppDateType::class)
            ->add('starttijd', AppTimeType::class,['required'=>false])
            ->add('einddatum', AppDateType::class,['required'=>false])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }
}
