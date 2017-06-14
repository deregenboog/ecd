<?php

namespace DagbestedingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\BaseType;
use DagbestedingBundle\Entity\Contactpersoon;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactpersoonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('soort', ChoiceType::class, [
                'choices' => Contactpersoon::SOORTEN,
                'placeholder' => '',
            ])
            ->add('naam')
            ->add('telefoon')
            ->add('email')
            ->add('opmerking')
            ->add('submit', SubmitType::class, ['label' => 'Opslaan'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contactpersoon::class,
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
