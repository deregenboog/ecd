<?php

namespace MwBundle\Form;

use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use MwBundle\Entity\Intake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeVerwachtingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('verwachtingen', AppTextareaType::class, [
                'label' => 'mw.intake.verwachting.verwachtingen.label',
            ])
            ->add('plannen', AppTextareaType::class, [
                'label' => 'mw.intake.verwachting.plannen.label',
            ])
            ->add('actiepunten', AppTextareaType::class, [
                'label' => 'mw.intake.verwachting.actiepunten.label',
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
            'data_class' => Intake::class,
            'class' => Intake::class,
            'mode' => BaseType::MODE_ADD,
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
