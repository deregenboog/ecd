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

class IntakeGezinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gezin', JaNeeType::class, [
                'label' => 'mw.intake.gezin.gezin.label',
                'help' => 'mw.intake.gezin.gezin.help',
            ])
            ->add('dreigendeDakloosheid', JaNeeType::class, [
                'label' => 'mw.intake.gezin.dreigendeDakloosheid.label',
                'required' => false,
            ])
            ->add('dreigendeDakloosheidToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.gezin.dreigendeDakloosheidToelichting.label',
                'required' => false,
            ])
            ->add('mog', JaNeeType::class, [
                'label' => 'mw.intake.gezin.mog.label',
                'required' => false,
            ])
            ->add('regiobinding', JaNeeType::class, [
                'label' => 'mw.intake.gezin.regiobinding.label',
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                    'Onduidelijk' => null,
                ],
            ])
            ->add('regiobindingToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.gezin.regiobindingToelichting.label',
                'required' => false,
            ])
            ->add('okt', JaNeeType::class, [
                'label' => 'mw.intake.gezin.okt.label',
                'required' => false,
            ])
            ->add('hulpverleningKinderen', JaNeeType::class, [
                'label' => 'mw.intake.gezin.hulpverleningKinderen.label',
                'required' => false,
            ])
            ->add('kinderbijslag', JaNeeType::class, [
                'label' => 'mw.intake.gezin.kinderbijslag.label',
                'required' => false,
            ])
            ->add('kindgebondenBudget', JaNeeType::class, [
                'label' => 'mw.intake.gezin.kindgebondenBudget.label',
                'required' => false,
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
