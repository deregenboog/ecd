<?php

namespace MwBundle\Form;

use AppBundle\Form\AppTextareaType;
use AppBundle\Form\BaseType;
use AppBundle\Form\JaNeeType;
use MwBundle\Entity\Intake;
use MwBundle\Entity\IntakeAdministratie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntakeAdministratieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('woningnet', JaNeeType::class, [
                'label' => 'mw.intake.administratie.woningnet.label',
            ])
            ->add('woningnetDuur', AppTextareaType::class, [
                'label' => 'mw.intake.administratie.woningnetDuur.label',
                'required' => false,
            ])
            ->add('verzekerd', JaNeeType::class, [
                'label' => 'mw.intake.administratie.verzekerd.label',
            ])
            ->add('digid', JaNeeType::class, [
                'label' => 'mw.intake.administratie.digid.label',
            ])
            ->add('huisarts', JaNeeType::class, [
                'label' => 'mw.intake.administratie.huisarts.label',
            ])
            ->add('huisartsContactgegevens', AppTextareaType::class, [
                'label' => 'mw.intake.administratie.huisartsContactgegevens.label',
                'required' => false,
            ])
            ->add('digitaalVaardig', JaNeeType::class, [
                'label' => 'mw.intake.administratie.digitaalVaardig.label',
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
            'class' => IntakeAdministratie::class,
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
