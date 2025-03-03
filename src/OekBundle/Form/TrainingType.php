<?php

namespace OekBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTimeType;
use AppBundle\Form\BaseType;
use OekBundle\Entity\Groep;
use OekBundle\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['data']) || !$options['data']->getGroep()) {
            $builder->add('groep', null, [
                'class' => Groep::class,
                'label' => 'Groep',
                'placeholder' => 'Selecteer een item',
                'query_builder' => function ( $er) {
                    return $er->createQueryBuilder('groep')
                        ->where('groep.actief = 1');
                },
            ]);
        }

        $builder
            ->add('naam')
            ->add('locatie')
            ->add('startdatum', AppDateType::class)
            ->add('starttijd', AppTimeType::class, ['required' => false])
            ->add('einddatum', AppDateType::class, ['required' => false])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }

    public function getParent(): ?string
    {
        return BaseType::class;
    }
}
