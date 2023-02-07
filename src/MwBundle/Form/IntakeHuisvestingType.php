<?php

namespace MwBundle\Form;

use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use MwBundle\Entity\Intake;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeHuisvestingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dakloosDuur', TextType::class, [
                'label' => 'mw.intake.huisvesting.dakloosDuur.label',
            ])
            ->add('dakloosOorzaak', AppTextareaType::class, [
                'label' => 'mw.intake.huisvesting.dakloosOorzaak.label',
            ])
            ->add('huisvesting', ChoiceType::class, [
                'label' => 'mw.intake.huisvesting.dakloosOorzaak.label',
                'placeholder' => 'Selecteer een optie',
                'choices' => [
                    'mw.intake.huisvesting.dakloosOorzaak.choice.straat' => 'straat',
                    'mw.intake.huisvesting.dakloosOorzaak.choice.familie' => 'familie',
                    'mw.intake.huisvesting.dakloosOorzaak.choice.hotel' => 'hotel',
                ],
            ])
            ->add('inschrijfadres', AppTextareaType::class, [
                'label' => 'Heeft de cliënt een inschrijfadres?',
            ])
            ->add('regiobinding', JaNeeType::class, [
                'label' => 'Heeft de cliënt (regio)binding?',
                'choices' => [
                    'Ja' => 1,
                    'Nee' => 0,
                    'Onduidelijk' => null,
                ],
            ])
            ->add('regiobindingToelichting', AppTextareaType::class, [
                'label' => 'Toelichting',
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
