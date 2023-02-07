<?php

namespace MwBundle\Form;

use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use MwBundle\Entity\Intake;
use MwBundle\Entity\IntakeWelzijn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeWelzijnType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $builder
            ->add('sociaalNetwerk', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.sociaalNetwerk.label',
            ])
            ->add('sociaalNetwerkBekend', JaNeeType::class, [
                'label' => 'mw.intake.welzijn.sociaalNetwerkBekend.label',
            ])
            ->add('sociaalNetwerkContactgegevens', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.sociaalNetwerkContactgegevens.label',
            ])
            ->add('andereInstanties', JaNeeType::class, [
                'label' => 'mw.intake.welzijn.andereInstanties.label',
            ])
            ->add('andereInstantiesContactgegevens', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.andereInstantiesContactgegevens.label',
                'help' => 'mw.intake.welzijn.andereInstantiesContactgegevens.help',
            ])
            ->add('dagstructuur', JaNeeType::class, [
                'label' => 'mw.intake.welzijn.dagstructuur.label',
                'help' => 'mw.intake.welzijn.dagstructuur.help',
            ])
            ->add('dagstructuurToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.dagstructuurToelichting.label',
                'help' => 'mw.intake.welzijn.dagstructuurToelichting.help',
            ])
            ->add('fysiekInOrde', JaNeeType::class, [
                'label' => 'mw.intake.welzijn.fysiekInOrde.label',
            ])
            ->add('fysiekToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.fysiekToelichting.label',
                'help' => 'mw.intake.welzijn.fysiekToelichting.help',
            ])
            ->add('psychischeProblemen', JaNeeType::class, [
                'label' => 'mw.intake.welzijn.psychischeProblemen.label',
            ])
            ->add('psychischeProblemenToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.psychischeProblemenToelichting.label',
                'help' => 'mw.intake.welzijn.psychischeProblemenToelichting.help',
            ])
            ->add('verslaving', ChoiceType::class, [
                'label' => 'mw.intake.welzijn.verslaving.label',
                'multiple' => true,
                'choices' => [
                    'mw.intake.welzijn.verslaving.choice.softdrugs' => 'softdrugs',
                    'mw.intake.welzijn.verslaving.choice.harddrugs' => 'harddrugs',
                    'mw.intake.welzijn.verslaving.choice.acohol' => 'acohol',
                    'mw.intake.welzijn.verslaving.choice.anders' => 'anders',
                ],
            ])
            ->add('verslavingToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.verslavingToelichting.label',
                'help' => 'mw.intake.welzijn.verslavingToelichting.help',
            ])
            ->add('justitie', JaNeeType::class, [
                'label' => 'mw.intake.welzijn.justitie.label',
            ])
            ->add('justitieToelichting', AppTextareaType::class, [
                'label' => 'mw.intake.welzijn.justitieToelichting.label',
                'help' => 'mw.intake.welzijn.justitieToelichting.help',
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
            'class' => IntakeWelzijn::class,
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
