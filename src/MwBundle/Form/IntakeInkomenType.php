<?php

namespace MwBundle\Form;

use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use MwBundle\Entity\Intake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeInkomenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inkomsten', JaNeeType::class, [
                'label' => 'mw.intake.inkomen.inkomsten.label',
            ])
            ->add('inkomstenToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.inkomen.inkomstenToelichting.label',
                'required' => false,
            ])
            ->add('hulpBijInkomen', JaNeeType::class, [
                'label' => 'mw.intake.inkomen.hulpBijInkomen.label',
            ])
            ->add('hulpBijInkomenToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.inkomen.hulpBijInkomenToelichting.label',
            ])
            ->add('stadspas', JaNeeType::class, [
                'label' => 'mw.intake.inkomen.stadspas.label',
            ])
            ->add('schuldenproblematiek', JaNeeType::class, [
                'label' => 'mw.intake.inkomen.schuldenproblematiek.label',
            ])
            ->add('schuldenproblematiekToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.inkomen.schuldenproblematiekToelichting.label',
                'help' => 'Vul hier een beschrijving in van de schulden van de cliënt en of er al hulp bij geboden wordt.',
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
