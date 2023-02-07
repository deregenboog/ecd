<?php

namespace MwBundle\Form;

use AppBundle\Form\AppDateType;
use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use AppBundle\Form\LandSelectType;
use AppBundle\Form\MedewerkerType;
use AppBundle\Form\TaalSelectType;
use InloopBundle\Form\VerblijfsstatusSelectType;
use MwBundle\Entity\Intake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeAlgemeenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datum', AppDateType::class, [
                'label' => 'mw.intake.algemeen.datum.label',
            ])
            ->add('medewerker', MedewerkerType::class, [
                'label' => 'mw.intake.algemeen.medewerker.label',
            ])
            ->add('geinformeerd', JaNeeType::class, [
                'label' => 'mw.intake.algemeen.geinformeerd.label',
            ])
            ->add('talen', TaalSelectType::class, [
                'label' => 'mw.intake.algemeen.talen.label',
                'multiple' => true,
            ])
            ->add('verblijfsstatus', VerblijfsstatusSelectType::class, [
                'label' => 'mw.intake.algemeen.verblijfsstatus.label',
                'multiple' => true,
            ])
            ->add('verblijfsstatusToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.algemeen.verblijfsstatusToelichting.label',
            ])
            ->add('landenRechthebbend', LandSelectType::class, [
                'label' => 'mw.intake.algemeen.landenRechthebbend.label',
                'multiple' => true,
            ])
            ->add('jvg', ChoiceType::class, [
                'label' => 'mw.intake.algemeen.jvg.label',
                'choices' => [
                    'mw.intake.algemeen.jvg.choice.niet_gescreend' => 'niet_gescreend',
                    'mw.intake.algemeen.jvg.choice.toegewezen' => 'toegewezen',
                    'mw.intake.algemeen.jvg.choice.afgewezen' => 'afgewezen',
                    'mw.intake.algemeen.jvg.choice.onbekend' => 'onbekend',
                ],
                'empty_data' => 'onbekend',
                'choice_translation_domain' => true,
            ])
            ->add('jvgToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.algemeen.jvgToelichting.label',
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
            'class' => IntakeAlgemeenType::class,
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
