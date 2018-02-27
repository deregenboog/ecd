<?php

namespace InloopBundle\Form;

use AppBundle\Form\AppDateTimeType;
use AppBundle\Form\BaseType;
use InloopBundle\Entity\Registratie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Opmerking;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Form\AppTextareaType;

class OpmerkingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', null, [
                'required' => true,
                'placeholder' => '',
            ])
            ->add('beschrijving', AppTextareaType::class, [
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
            'data_class' => Opmerking::class,
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
