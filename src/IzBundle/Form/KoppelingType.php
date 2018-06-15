<?php

namespace IzBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\BaseType;
use IzBundle\Entity\Hulpvraag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KoppelingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('koppelingStartdatum', AppDateType::class, [
                'label' => 'Startdatum koppeling',
                'required' => true,
            ])
            ->add('tussenevaluatiedatum', AppDateType::class, [
                'label' => 'Datum tussenevaluatie',
                'required' => true,
            ])
            ->add('eindevaluatiedatum', AppDateType::class, [
                'label' => 'Datum eindevaluatie',
                'required' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hulpvraag::class,
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
